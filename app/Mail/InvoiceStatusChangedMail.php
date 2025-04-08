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
    public $qrCid; // Content ID untuk embed image

    public function __construct(Invoice $invoice, $status)
    {
        $this->invoice = $invoice;
        $this->status = $status;
    }

    public function build()
    {
        // URL untuk PDF invoice
        $pdfUrl = route('invoices.download', $this->invoice->id);

        // Generate QR code PNG binary
        $qrCodeBinary = QrCode::format('png')->size(200)->generate($pdfUrl);

        // Embed QR code sebagai attachment inline (embedded image)
        $qrCid = $this->attachData($qrCodeBinary, 'qrcode.png', [
            'mime' => 'image/png',
        ])->embedData($qrCodeBinary, 'qrcode.png', 'image/png');

        $this->qrCid = $qrCid;

        return $this->subject("Perubahan Status Invoice #{$this->invoice->invoice_number}")
                    ->view('emails.invoice.status')
                    ->with([
                        'invoice' => $this->invoice,
                        'status' => $this->status,
                        'qrCid' => $this->qrCid,
                    ]);
    }
}
