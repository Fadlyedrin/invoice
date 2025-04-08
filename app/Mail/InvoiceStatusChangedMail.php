<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class InvoiceStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $status;
    public $qrCodePath;

    public function __construct(Invoice $invoice, $status)
    {
        $this->invoice = $invoice;
        $this->status = $status;

        // Generate QR Code dan simpan ke storage sementara
        $pdfUrl = route('invoices.download', $this->invoice->id);
        $qrCode = QrCode::format('png')->size(200)->generate($pdfUrl);

        $this->qrCodePath = 'qrcodes/invoice-' . $invoice->id . '.png';
        Storage::disk('public')->put($this->qrCodePath, $qrCode);
    }

    public function build()
    {
        return $this->subject("Perubahan Status Invoice #{$this->invoice->invoice_number}")
                    ->markdown('emails.invoice.status')
                    ->with([
                        'invoice' => $this->invoice,
                        'status' => $this->status,
                        'qrCodeUrl' => asset('storage/' . $this->qrCodePath),
                    ])
                    ->attach(storage_path('app/public/' . $this->qrCodePath), [
                        'as' => 'qrcode-invoice.png',
                        'mime' => 'image/png',
                    ]);
    }
}