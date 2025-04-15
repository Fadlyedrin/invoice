<?php

namespace App\Mail;

use App\Models\Receipt;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;

class ReceiptStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $receipt;
    public $status;
    public $actor;
    public $qrCodePath;

    public function __construct(Receipt $receipt, $status, $actor = null)
    {
        $this->receipt = $receipt;
        $this->status = $status;
        $this->actor = $actor;

        if ($receipt->status === 'Disetujui') {
            $pdfUrl = route('receipts.download', $receipt->id);
            $qrCode = QrCode::format('png')->size(200)->generate($pdfUrl);

            $this->qrCodePath = 'qrcodes/receipt-' . $receipt->id . '.png';
            Storage::disk('public')->put($this->qrCodePath, $qrCode);
        } else {
            $this->qrCodePath = null;
        }
    }

    public function build()
    {
        $mail = $this->subject("Perubahan Status Receipt untuk Invoice #{$this->receipt->invoice->invoice_number}")
                    ->view('emails.receipt.status')
                    ->with([
                        'receipt' => $this->receipt,
                        'status' => $this->status,
                        'actor' => $this->actor,
                        'qrCodeUrl' => $this->qrCodePath ? asset('storage/' . $this->qrCodePath) : null,
                    ]);

        if ($this->qrCodePath) {
            $mail->attach(storage_path('app/public/' . $this->qrCodePath), [
                'as' => 'qrcode-receipt.png',
                'mime' => 'image/png',
            ]);
        }

        return $mail;
    }
}