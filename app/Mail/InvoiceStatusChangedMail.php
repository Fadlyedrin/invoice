<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class InvoiceStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;
    public $status;
    public $qrCodeBase64;

    public function __construct(Invoice $invoice, $status)
    {
        $this->invoice = $invoice;
        $this->status = $status;

        // Buat URL untuk download PDF invoice
        $pdfUrl = route('invoices.download', $this->invoice->id);

        // Generate QR Code dalam bentuk PNG (binary)
        $qrCodeBinary = QrCode::format('png')->size(200)->generate($pdfUrl);

        // Encode QR Code ke base64 untuk ditampilkan inline di email
        $this->qrCodeBase64 = base64_encode($qrCodeBinary);
    }

    public function build()
    {
        return $this->subject("Perubahan Status Invoice #{$this->invoice->invoice_number}")
                    ->view('emails.invoice.status')
                    ->with([
                        'invoice' => $this->invoice,
                        'status' => $this->status,
                        'qrCodeBase64' => $this->qrCodeBase64, // dikirim ke view
                    ]);
    }
}
