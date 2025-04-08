@component('mail::message')
# Notifikasi Perubahan Status Receipt

Receipt untuk Invoice: *{{ $receipt->invoice->invoice_number }}*
Jumlah Dibayar: *Rp{{ number_format($receipt->amount_paid, 2) }}*
Metode Pembayaran: *{{ $receipt->payment_method }}*
Status Saat Ini: *{{ $receipt->status }}*

@isset($receipt->draft_data['approval_reason'])
*Alasan Disetujui*: {{ $receipt->draft_data['approval_reason'] }}
@endisset

@isset($receipt->draft_data['rejection_reason'])
*Alasan Ditolak*: {{ $receipt->draft_data['rejection_reason'] }}
@endisset

@component('mail::button', ['url' => route('receipts.show', $receipt->id)])
Lihat Detail Receipt
@endcomponent

*Unduh PDF Receipt (Scan QR Code):*
[<img src="{{ $qrCodeUrl }}" alt="QR Code" width="150">]({{ route('receipts.download', $receipt->id) }})

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent