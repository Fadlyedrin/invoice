<?php

namespace App\Http\Controllers;

use Log;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceStatusChangedMail;


class InvoiceController extends Controller
{
       public function __construct()
    {
        $this->middleware('permission:invoices')->only(['index']);
        $this->middleware('permission:create invoices')->only(['create', 'store']);
        $this->middleware('permission:update invoices')->only(['edit', 'update']);
        $this->middleware('permission:delete invoices')->only(['destroy']);
    }

/**
 * Validasi apakah tanggal yang dihasilkan valid
 *
 * @param string $date Format Y-m-d
 * @return bool
 */
private function isValidDate($date)
{
    $d = \DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
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


   public function store(Request $request)
{
    $request->validate([
        'description' => 'nullable|string',
        'status' => 'required|in:Draft,Menunggu Approval',
        'payment_status' => 'required|in:Pending,Partial,Complete',
        'items' => 'required|array|min:1',
        'items.*.item_name' => 'required|string',
        'items.*.quantity' => 'required|numeric|min:1',
        'items.*.price_per_item' => 'required|numeric|min:0',
    ]);

    // Generate invoice number
    $romanMonth = $this->convertToRoman(date('m'));
    $prefix = 'INV/BSM/' . date('Y') . '/' . $romanMonth . '/' . date('d') . '/';
    $sequence = str_pad((Invoice::count() + 1), 4, '0', STR_PAD_LEFT);
    $invoiceNumber = $prefix . $sequence;

    $totalAmount = collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price_per_item']);

    $invoice = Invoice::create([
        'invoice_number' => $invoiceNumber,
        'description' => $request->description,
        'amount' => $totalAmount,
        'status' => $request->status,
        'payment_status' => $request->payment_status,
        'created_by' => auth()->id(),
    ]);

    foreach ($request->items as $item) {
        $parsedDetails = [];

        if (!empty($item['item_details'])) {
            $niks = json_decode($item['item_details'], true);

            if (!is_array($niks)) {
                return back()->withErrors(['Item details harus berupa array: ' . json_encode($item['item_details'])])->withInput();
            }

            foreach ($niks as $obj) {
                if (!is_array($obj) || !isset($obj['nik']) || !is_string($obj['nik'])) {
                    return back()->withErrors(['Item details harus berupa array of object dengan key "nik": ' . json_encode($niks)])->withInput();
                }
                $nik = $obj['nik'];

                $day = (int) substr($nik, 6, 2);
                $month = (int) substr($nik, 8, 2);
                $year = (int) substr($nik, 10, 2);

                $gender = 'Laki-laki';
                if ($day > 40) {
                    $gender = 'Perempuan';
                    $day -= 40;
                }

                $fullYear = ($year <= (int) date('y')) ? 2000 + $year : 1900 + $year;
                $birthDate = sprintf('%04d-%02d-%02d', $fullYear, $month, $day);

                if (!preg_match('/^\d{16}$/', $nik)) {
                    return back()->withErrors(['NIK tidak valid: ' . $nik])->withInput();
                }

                $kodeProv = substr($nik, 0, 2);
                $kodeKota = substr($nik, 0, 4);
                $kodeKec = substr($nik, 0, 6);

                $provinsi = DB::table('indonesia_provinces')->where('code', $kodeProv)->value('name');
                $kota = DB::table('indonesia_cities')->where('code', $kodeKota)->value('name');
                $kecamatan = DB::table('indonesia_districts')->where('code', $kodeKec)->value('name');

                $parsedDetails[] = [
                    'nik' => $nik,
                    'nama' => $obj['nama'] ?? '-',
                    'provinsi' => $provinsi ?? '-',
                    'kota' => $kota ?? '-',
                    'kecamatan' => $kecamatan ?? '-',
                    'jenis_kelamin' => $gender,
                    'tanggal_lahir' => $birthDate,
                ];
            }
        }

        $invoice->items()->create([
            'item_name' => $item['item_name'],
            'item_details' => json_encode($parsedDetails),
            'quantity' => $item['quantity'],
            'price_per_item' => $item['price_per_item'],
            'total_price' => $item['quantity'] * $item['price_per_item'],
        ]);
    }

    return redirect()->route('invoices.index')->with('success', 'Invoice berhasil dibuat.');
}


public function update(Request $request, Invoice $invoice)
{
    if (!in_array($invoice->status, ['Draft', 'Menunggu Approval'])) {
        return redirect()->back()->with('error', 'Invoice tidak bisa diubah pada status ini.');
    }

    $request->validate([
        'items' => 'required|array|min:1',
        'items.*.item_name' => 'required|string',
        'items.*.quantity' => 'required|numeric|min:1',
        'items.*.price_per_item' => 'required|numeric|min:0',
    ]);

    $totalAmount = collect($request->items)->sum(fn($item) => $item['quantity'] * $item['price_per_item']);
    $invoice->update(['amount' => $totalAmount]);

    // Hapus item yang tidak dikirim ulang
    $submittedItemIds = collect($request->items)->pluck('id')->filter()->toArray();
    $invoice->items()->whereNotIn('id', $submittedItemIds)->delete();

    foreach ($request->items as $item) {
        $parsedDetails = [];

        if (!empty($item['item_details'])) {
            $niks = json_decode($item['item_details'], true);

            if (!is_array($niks)) {
                return back()->withErrors(['Item details harus berupa array'])->withInput();
            }

            foreach ($niks as $obj) {
                if (!is_array($obj) || !isset($obj['nik']) || !is_string($obj['nik'])) {
                    return back()->withErrors(['Item details harus berupa array of object dengan key "nik"'])->withInput();
                }

                $nik = $obj['nik'];

                $day = (int) substr($nik, 6, 2);
                $month = (int) substr($nik, 8, 2);
                $year = (int) substr($nik, 10, 2);

                $gender = 'Laki-laki';
                if ($day > 40) {
                    $gender = 'Perempuan';
                    $day -= 40;
                }

                $fullYear = ($year <= (int) date('y')) ? 2000 + $year : 1900 + $year;
                $birthDate = sprintf('%04d-%02d-%02d', $fullYear, $month, $day);
                $kodeProv = substr($obj['nik'], 0, 2);
                $kodeKota = substr($obj['nik'], 0, 4);
                $kodeKec = substr($obj['nik'], 0, 6);

                $provinsi = DB::table('indonesia_provinces')->where('code', $kodeProv)->value('name');
                $kota = DB::table('indonesia_cities')->where('code', $kodeKota)->value('name');
                $kecamatan = DB::table('indonesia_districts')->where('code', $kodeKec)->value('name');

                $parsedDetails[] = [
                    'nik' => $obj['nik'],
                    'nama' => $obj['nama'] ?? '-',
                    'provinsi' => $provinsi ?? '-',
                    'kota' => $kota ?? '-',
                    'kecamatan' => $kecamatan ?? '-',
                    'jenis_kelamin' => $gender,
                    'tanggal_lahir' => $birthDate,
                ];
            }
        }

        if (isset($item['id']) && $item['id']) {
            $existingItem = $invoice->items()->find($item['id']);
            if ($existingItem) {
                $existingItem->update([
                    'item_name' => $item['item_name'],
                    'item_details' => json_encode($parsedDetails),
                    'quantity' => $item['quantity'],
                    'price_per_item' => $item['price_per_item'],
                    'total_price' => $item['quantity'] * $item['price_per_item'],
                ]);
            }
        } else {
            $invoice->items()->create([
                'item_name' => $item['item_name'],
                'item_details' => json_encode($parsedDetails),
                'quantity' => $item['quantity'],
                'price_per_item' => $item['price_per_item'],
                'total_price' => $item['quantity'] * $item['price_per_item'],
            ]);
        }
    }

    return redirect()->route('invoices.index')->with('success', 'Invoice berhasil diperbarui.');
}

public function edit(Invoice $invoice)
{
    // Only allow editing if status is Draft or Menunggu Approval
    if (!in_array($invoice->status, ['Draft', 'Menunggu Approval', 'Ditolak'])) {
        return redirect()->back()->with('error', 'Invoice tidak dapat diubah karena status-nya sudah final.');
    }

    // Tambahkan eager loading relasi items
    $invoice->load('items');

    return view('invoices.edit', compact('invoice'));
}


/**
 * Convert number to Roman numeral
 *
 * @param int $number
 * @return string
 */
private function convertToRoman($number)
{
    $romans = [
        1 => 'I',
        2 => 'II',
        3 => 'III',
        4 => 'IV',
        5 => 'V',
        6 => 'VI',
        7 => 'VII',
        8 => 'VIII',
        9 => 'IX',
        10 => 'X',
        11 => 'XI',
        12 => 'XII'
    ];

    return $romans[(int)$number] ?? '';
}

    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }


    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
    }

    public function approve(Request $request, Invoice $invoice)
    {
        if (!in_array($invoice->status, ['Draft', 'Menunggu Approval'])) {
            return redirect()->back()->with('error', 'Invoice tidak dapat disetujui karena status-nya sudah final.');
        }

        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        $invoice->status = 'Disetujui';
        $invoice->draft_data = ['approval_reason' => $request->reason];
        $invoice->save();

        $this->sendStatusChangeEmail($invoice, 'approved', auth()->user());

        return redirect()->back()->with('success', 'Invoice disetujui.');
    }

    public function reject(Request $request, Invoice $invoice)
    {
        if (!in_array($invoice->status, ['Draft', 'Menunggu Approval'])) {
            return redirect()->back()->with('error', 'Invoice tidak dapat ditolak karena status-nya sudah final.');
        }

        $request->validate([
            'reason' => 'required|string|min:10'
        ]);

        $invoice->status = 'Ditolak';
        $invoice->draft_data = ['rejection_reason' => $request->reason];
        $invoice->save();

        $this->sendStatusChangeEmail($invoice, 'rejected', auth()->user());

        return redirect()->back()->with('success', 'Invoice ditolak.');
    }

    private function sendStatusChangeEmail(Invoice $invoice, $status, $actor = null)
    {
        $recipients = [];

        // Creator email
        if ($invoice->creator && $invoice->creator->email) {
            $recipients[] = $invoice->creator->email;
        }

        // Actor email (who approved/rejected)
        if ($actor && $actor->email && $actor->id !== optional($invoice->creator)->id) {
            $recipients[] = $actor->email;
        }

        foreach ($recipients as $email) {
            Mail::to($email)->send(new InvoiceStatusChangedMail($invoice, $status, $actor));
        }
    }

    public function download(Invoice $invoice)
    {
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));

        $sanitizedInvoiceNumber = str_replace(['/', '\\'], '-', $invoice->invoice_number);

        return $pdf->download("Invoice-{$sanitizedInvoiceNumber}.pdf");
    }
}
