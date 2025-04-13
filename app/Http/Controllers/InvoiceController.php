<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
public function __construct()
{

    $this->middleware('permission:invoices')->only(['index']);
    $this->middleware('permission:create invoices')->only(['create', 'store']);
    $this->middleware('permission:update invoices')->only(['edit', 'update']);
    $this->middleware('permission:delete invoices')->only(['destroy']);
    $this->middleware('permission:approve invoices')->only(['show']);
}
public function index()
{
    $user = Auth::user();
    
    // Check if user has admin_pusat role
    if ($user->hasRole('admin pusat')) {
        // Admin pusat can see all invoices
        $invoices = Invoice::with(['items', 'creator'])->latest()->get();
    } else {
        // Regular admins can only see their own invoices
        $invoices = Invoice::with(['items', 'creator'])->where('created_by', $user->id)->latest()->get();
    }
    
    return view('invoices.index', compact('invoices'));
}

    public function create()
    {
        
        return view('invoices.create');
    }

// InvoiceController.php

public function store(Request $request)
{
    // Validasi input
    $request->validate([
        'description' => 'nullable|string',
        'status' => 'required|in:Draft,Menunggu Approval',
        'payment_status' => 'required|in:Pending,Partial,Complete',
        'items' => 'required|array',
        'items.*.item_name' => 'required|string',
        'items.*.quantity' => 'required|numeric|min:1',
        'items.*.price_per_item' => 'required|numeric|min:0',
    ]);

    // Generate nomor invoice yang benar-benar unik (termasuk soft deleted)
    $prefix = 'INV-' . date('Ymd') . '-';
    $lastInvoice = Invoice::withTrashed()
        ->where('invoice_number', 'like', $prefix . '%')
        ->orderBy('invoice_number', 'desc')
        ->first();

    $lastNumber = 0;
    if ($lastInvoice) {
        $lastNumber = intval(substr($lastInvoice->invoice_number, -4));
    }

    $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    $invoiceNumber = $prefix . $newNumber;

    // Hitung total amount dari semua item
    $totalAmount = collect($request->items)->sum(function ($item) {
        return $item['quantity'] * $item['price_per_item'];
    });

    // Simpan invoice ke database
    $invoice = Invoice::create([
        'invoice_number' => $invoiceNumber,
        'description' => $request->description,
        'amount' => $totalAmount,
        'status' => $request->status,
        'payment_status' => $request->payment_status,
        'created_by' => auth()->id(),
    ]);

    // Simpan item-item invoice
    foreach ($request->items as $item) {
        $invoice->items()->create([
            'item_name' => $item['item_name'],
            'quantity' => $item['quantity'],
            'price_per_item' => $item['price_per_item'],
            'total_price' => $item['quantity'] * $item['price_per_item'],
        ]);
    }

    return redirect()->route('invoices.index')->with('success', 'Invoice created successfully');
}


    public function show(Invoice $invoice)
    {
        
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        // Only allow editing if status is Draft or Menunggu Approval
        if (!in_array($invoice->status, ['Draft', 'Menunggu Approval'])) {
            return redirect()->back()->with('error', 'Cannot edit invoice in current status.');
        }

        return view('invoices.edit', compact('invoice'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        // Batasi update hanya jika status masih Draft / Menunggu Approval
        if (!in_array($invoice->status, ['Draft', 'Menunggu Approval'])) {
            return redirect()->back()->with('error', 'Invoice tidak bisa diubah pada status ini.');
        }

        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.price_per_item' => 'required|numeric|min:0',
        ]);

        // Hitung ulang total amount
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $totalAmount += $item['quantity'] * $item['price_per_item'];
        }

        $oldStatus = $invoice->status;

        // Update hanya amount
        $invoice->update([
            'amount' => $totalAmount,
        ]);

        // Hapus semua item lama
        $invoice->items()->delete();

        // Simpan item baru
        foreach ($request->items as $item) {
            $invoice->items()->create([
                'item_name' => $item['item_name'],
                'quantity' => $item['quantity'],
                'price_per_item' => $item['price_per_item'],
                'total_price' => $item['quantity'] * $item['price_per_item'],
            ]);
        }

        // Kirim email kalau status berubah (backup)
        if ($oldStatus !== $invoice->status) {
            $this->sendStatusChangeEmail($invoice, 'status_changed');
        }

        return redirect()->route('invoices.index')->with('success', 'Invoice berhasil diperbarui.');
    }


    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
    }

    //new
    public function approve(Request $request, Invoice $invoice)
    {
        if (!in_array($invoice->status, ['Draft', 'Menunggu Approval'])) {
            return redirect()->back()->with('error', 'Invoice tidak dapat disetujui karena status-nya sudah final.');
        }

        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        $invoice->status = 'Disetujui';
        $invoice->draft_data = [
            'approval_reason' => $request->reason,
            'approved_by' => auth()->user()->name
        ];
        $invoice->save();

        $this->sendStatusChangeEmail($invoice, 'approved', auth()->user());

        return redirect()->back()->with('success', 'Invoice disetujui.');
    }
    
    //new
    public function reject(Request $request, Invoice $invoice)
    {
        if (!in_array($invoice->status, ['Draft', 'Menunggu Approval'])) {
            return redirect()->back()->with('error', 'Invoice tidak dapat ditolak karena status-nya sudah final.');
        }

        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        $invoice->status = 'Ditolak';
        $invoice->draft_data = [
            'rejection_reason' => $request->reason
        ];
        $invoice->save();

        $this->sendStatusChangeEmail($invoice, 'rejected', auth()->user());

        return redirect()->back()->with('success', 'Invoice ditolak.');
    }
    
    //new
    private function sendStatusChangeEmail(Invoice $invoice, $status, $actionBy = null)
    {
        $creatorEmail = optional($invoice->creator)->email;
        $approverEmail = optional($actionBy)->email;

        $receipients = collect([$creatorEmail, $approverEmail])->filter();

        foreach ($receipients as $email) {
            Mail::to($email)->send(new \App\Mail\InvoiceStatusChangedMail($invoice, $status));
        }
    }


     public function download(Invoice $invoice)
     {
         $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
         return $pdf->download("Invoice-{$invoice->invoice_number}.pdf");
     }
}
