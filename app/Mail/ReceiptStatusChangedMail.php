<?php

namespace App\Mail;

use App\Models\Receipt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReceiptStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receipt;
    public $status;
    public $qrCodeBase64;

    public function __construct(Receipt $receipt, $status)
    {
        $this->receipt = $receipt;
        $this->status = $status;

        // Hanya generate QR Code jika status disetujui
        if ($receipt->status === 'Disetujui') {
            $pdfUrl = route('receipts.download', $receipt->id);

            // Generate QR Code dalam format PNG base64
            $qrCodePng = QrCode::format('png')
                ->size(200)
                ->generate($pdfUrl);

            // Encode ke base64 dan simpan di properti
            $this->qrCodeBase64 = 'data:image/png;base64,' . base64_encode($qrCodePng);
        } else {
            $this->qrCodeBase64 = null;
        }
    }

    public function build()
    {
        return $this->subject("Perubahan Status Receipt untuk Invoice #{$this->receipt->invoice->invoice_number}")
            ->markdown('emails.receipt.status')
            ->with([
                'receipt' => $this->receipt,
                'status' => $this->status,
                'qrCodeBase64' => $this->qrCodeBase64,
            ]);
    }
}
