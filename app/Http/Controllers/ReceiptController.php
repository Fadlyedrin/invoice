<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Mail\ReceiptStatusChangedMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ReceiptController extends Controller
{

    public function __construct()
{

    $this->middleware('permission:receipt')->only(['index']);
    $this->middleware('permission:create receipt')->only(['create', 'store']);
    $this->middleware('permission:update receipt')->only(['edit', 'update']);
    $this->middleware('permission:delete receipt')->only(['destroy']);
    $this->middleware('permission:approve receipt')->only(['show']);
}
public function index()
{
    $user = Auth::user();
    
    // Check if user has admin_pusat role
    if ($user->hasRole('admin pusat')) {
        // Admin pusat can see all receipts
        $receipts = Receipt::with('invoice')->latest()->get();
    } else {
        // Regular admins can only see receipts from their invoices
        $receipts = Receipt::with('invoice')->whereRelation('invoice', 'created_by', $user->id)->latest()->get();
    }
    
    return view('receipts.index', compact('receipts'));
}


    public function create()
    {
        $invoices = Invoice::where('created_by', auth()->id())
                    ->where('status', 'Disetujui')
                    ->doesntHave('receipt') // agar hanya invoice yang belum punya receipt
                    ->get();

        $invoicee = Invoice::get();

        return view('receipts.create', compact('invoices', 'invoicee'));
    }



public function store(Request $request)
{
    $request->validate([
        'invoice_id' => 'required|exists:invoices,id|unique:receipts,invoice_id',
        'amount_paid' => 'required|numeric|min:0',
        'payment_method' => 'required|in:Cash,Credit Card,Bank Transfer',
        'payment_date' => 'required|date',
        'payment_status' => 'required|in:Pending,Partial,Complete',
    ]);

    // Generate Receipt Number
    $prefix = 'RCP/BSM/' . date('Y') . '/' . $this->convertToRoman(date('m')) . '/' . date('d') . '/';
    $lastReceipt = Receipt::withTrashed()
        ->where('receipt_number', 'like', $prefix . '%')
        ->orderBy('id', 'desc')
        ->first();
    $lastNumber = $lastReceipt ? intval(substr($lastReceipt->receipt_number, strrpos($lastReceipt->receipt_number, '/') + 1)) : 0;
    $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    $receiptNumber = $prefix . $newNumber;

    // Simpan Receipt baru
    $receipt = Receipt::create([
        'invoice_id' => $request->invoice_id,
        'amount_paid' => $request->amount_paid,
        'payment_method' => $request->payment_method,
        'status' => 'Draft',
        'payment_date' => $request->payment_date,
        'draft_data' => null,
        'receipt_number' => $receiptNumber, // <- Tambahkan di sini
    ]);

    // Update status Invoice
    $receipt->invoice->update([
        'payment_status' => $request->payment_status
    ]);

    return redirect()->route('receipts.index')->with('success', 'Receipt berhasil dibuat.');
}

// Helper function untuk bulan romawi
private function convertToRoman($month)
{
    $romanMonths = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
        5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
        9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
    ];
    return $romanMonths[intval($month)];
}



    public function edit(Receipt $receipt)
{
    if (!in_array($receipt->status, ['Draft', 'Menunggu Approval', 'Ditolak'])) {
        return redirect()->back()->with('error', 'Receipt tidak dapat diubah karena status-nya sudah final.');
    }

    // Hanya invoice yang disetujui & milik user, tapi tetap tampilkan invoice milik receipt ini
    $invoices = Invoice::where('created_by', auth()->id())
        ->where('status', 'Disetujui')
        ->orWhere('id', $receipt->invoice_id) // supaya invoice yang dipakai sekarang tetap muncul
        ->get();

    return view('receipts.edit', compact('receipt', 'invoices'));
}

public function update(Request $request, Receipt $receipt)
{
    if (!in_array($receipt->status, ['Draft', 'Menunggu Approval', 'Ditolak'])) {
        return redirect()->back()->with('error', 'Receipt tidak dapat diubah karena status-nya sudah final.');
    }

    $request->validate([
        'amount_paid' => 'required|numeric|min:0',
        'payment_method' => 'required|in:Cash,Credit Card,Bank Transfer',
        'payment_date' => 'required|date',
        'payment_status' => 'required|in:Pending,Partial,Complete',
        'change_status' => 'sometimes|boolean', 
    ]);

    // Jika receipt_number masih kosong, buat baru
    if (empty($receipt->receipt_number)) {
        $prefix = 'RCP/BSM/' . date('Y') . '/' . $this->convertToRoman(date('m')) . '/' . date('d') . '/';
        $lastReceipt = Receipt::withTrashed()
            ->where('receipt_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
        $lastNumber = $lastReceipt ? intval(substr($lastReceipt->receipt_number, strrpos($lastReceipt->receipt_number, '/') + 1)) : 0;
        $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $receiptNumber = $prefix . $newNumber;

        $receipt->receipt_number = $receiptNumber;
    }

    // Jika receipt berstatus Ditolak dan ada permintaan untuk mengubah statusnya
    if ($receipt->status === 'Ditolak' && $request->change_status) {
        $receipt->status = 'Menunggu Approval';
    }

    // Update data receipt
    $receipt->update([
        'amount_paid' => $request->amount_paid,
        'payment_method' => $request->payment_method,
        'payment_date' => $request->payment_date,
        'status' => $receipt->status,
        'receipt_number' => $receipt->receipt_number, // Pastikan receipt_number tetap ada
    ]);

    // Update juga status invoice
    $receipt->invoice->update([
        'payment_status' => $request->payment_status
    ]);

    return redirect()->route('receipts.index')->with('success', 'Receipt berhasil diperbarui.');
}



    public function show(Receipt $receipt)
    {
        return view('receipts.show', compact('receipt'));
    }
public function approve(Request $request, Receipt $receipt)
    {
        if (!in_array($receipt->status, ['Draft', 'Menunggu Approval'])) {
            return redirect()->back()->with('error', 'Receipt tidak dapat disetujui karena status-nya sudah final.');
        }

        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        $receipt->status = 'Disetujui';
        $receipt->draft_data = ['approval_reason' => $request->reason];
        $receipt->save();

        $this->sendStatusChangeEmail($receipt, 'approved', auth()->user());

        return redirect()->route('receipts.index')->with('success', 'Receipt disetujui.');
    }

    public function reject(Request $request, Receipt $receipt)
    {
        if (!in_array($receipt->status, ['Draft', 'Menunggu Approval'])) {
            return redirect()->back()->with('error', 'Receipt tidak dapat ditolak karena status-nya sudah final.');
        }

        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        $receipt->status = 'Ditolak';
        $receipt->draft_data = ['rejection_reason' => $request->reason];
        $receipt->save();

        $this->sendStatusChangeEmail($receipt, 'rejected', auth()->user());

        return redirect()->route('receipts.index')->with('success', 'Receipt ditolak.');
    }

    private function sendStatusChangeEmail(Receipt $receipt, $status, $actor = null)
    {
        $recipients = [];

        // Creator dari invoice
        if ($receipt->invoice && $receipt->invoice->creator && $receipt->invoice->creator->email) {
            $recipients[] = $receipt->invoice->creator->email;
        }

        // Admin yang approve/reject
        if ($actor && $actor->email && $actor->id !== optional($receipt->invoice->creator)->id) {
            $recipients[] = $actor->email;
        }

        foreach ($recipients as $email) {
            Mail::to($email)->send(new ReceiptStatusChangedMail($receipt, $status, $actor));
        }
    }

    public function destroy(Receipt $receipt)
    {
        $receipt->delete();
        return redirect()->route('receipts.index')->with('success', 'Receipt berhasil dihapus.');
    }

    public function download(Receipt $receipt)
    {
        $pdf = Pdf::loadView('receipts.pdf', compact('receipt'));
        return $pdf->download("receipt-{$receipt->id}.pdf");
    }
}

