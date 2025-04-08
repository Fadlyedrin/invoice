<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Status Invoice</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px;">
    <h2>Notifikasi Perubahan Status Invoice</h2>

    <p>
        Invoice: <strong>{{ $invoice->invoice_number }}</strong><br>
        Status Saat Ini: <strong>{{ $invoice->status }}</strong><br>
    </p>

    @isset($invoice->draft_data['approval_reason'])
        <p><strong>Alasan Disetujui:</strong> {{ $invoice->draft_data['approval_reason'] }}</p>
    @endisset

    @isset($invoice->draft_data['rejection_reason'])
        <p><strong>Alasan Ditolak:</strong> {{ $invoice->draft_data['rejection_reason'] }}</p>
    @endisset

    <p>
        <a href="{{ route('invoices.show', $invoice->id) }}" style="display:inline-block; padding:10px 20px; background-color:#2d3748; color:white; text-decoration:none; border-radius:5px;">Lihat Invoice</a>
    </p>

    @if (!empty($qrCid))
        <p><strong>Scan QR Code untuk unduh PDF Invoice:</strong></p>
        <img src="{{ $qrCid }}" alt="QR Code" width="200">
    @endif

    <p>Terima kasih,</p>
</body>
</html>
