<h1>Notifikasi Perubahan Status Receipt</h1>

<p>Receipt untuk Invoice: <strong>{{ $receipt->invoice->invoice_number }}</strong></p>
<p>Jumlah Dibayar: <strong>Rp{{ number_format($receipt->amount_paid, 2) }}</strong></p>
<p>Metode Pembayaran: <strong>{{ $receipt->payment_method }}</strong></p>
<p>Status Saat Ini: <strong>{{ $receipt->status }}</strong></p>

@if(isset($receipt->draft_data['approval_reason']))
    <p><strong>Alasan Disetujui:</strong> {{ $receipt->draft_data['approval_reason'] }}</p>
@endif

@if(isset($receipt->draft_data['rejection_reason']))
    <p><strong>Alasan Ditolak:</strong> {{ $receipt->draft_data['rejection_reason'] }}</p>
@endif

@if($actor)
*Perubahan dilakukan oleh*: {{ $actor->name }} ({{ $actor->email }})
@endif

<p><a href="{{ route('receipts.show', $receipt->id) }}">Lihat Detail Receipt</a></p>

@if($receipt->status === 'Disetujui')
    <p><strong>Scan QR Code untuk Unduh Receipt:</strong></p>
    <img src="{{ $qrCodeUrl }}" width="150" alt="QR Code">
@endif

<p>Terima kasih,<br>{{ config('app.name') }}</p>