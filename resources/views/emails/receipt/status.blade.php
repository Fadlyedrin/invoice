@component('mail::message')
# Notifikasi Perubahan Status Receipt

Receipt untuk Invoice: **{{ $receipt->invoice->invoice_number }}**  
Jumlah Dibayar: **Rp{{ number_format($receipt->amount_paid, 2) }}**  
Metode Pembayaran: **{{ $receipt->payment_method }}**  
Status Saat Ini: **{{ $receipt->status }}**

@isset($receipt->draft_data['approval_reason'])
**Alasan Disetujui**: {{ $receipt->draft_data['approval_reason'] }}
@endisset

@isset($receipt->draft_data['rejection_reason'])
**Alasan Ditolak**: {{ $receipt->draft_data['rejection_reason'] }}
@endisset

@component('mail::button', ['url' => route('receipts.show', $receipt->id)])
Lihat Detail Receipt
@endcomponent

@if ($qrCodeBase64)
<p><strong>Scan QR Code untuk download PDF:</strong></p>
{!! '<img src="' . $qrCodeBase64 . '" alt="QR Code" width="200">' !!}
@endif

Terima kasih,<br>
@endcomponent
