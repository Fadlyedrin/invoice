<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Receipt #{{ $receipt->invoice->invoice_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; color: #333; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 1px solid #ccc; margin-bottom: 20px; }
        .title { font-size: 20px; font-weight: bold; }
        .section { margin-bottom: 20px; }
        .label { font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td, th { padding: 8px; border: 1px solid #ccc; text-align: left; }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="title">Receipt #{{ $receipt->invoice->invoice_number }}</div>
        <div>Tanggal Pembayaran: {{ \Carbon\Carbon::parse($receipt->payment_date)->format('d M Y') }}</div>
    </div>

    <div class="section">
        <h4>Detail Item Invoice</h4>
        <table>
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($receipt->invoice->items as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price_per_item, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="section">
        <h4>Detail Pembayaran</h4>
        <table>
            <tr>
                <th>Metode Pembayaran</th>
                <th>Jumlah Dibayar</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>{{ $receipt->payment_method }}</td>
                <td>Rp {{ number_format($receipt->amount_paid, 0, ',', '.') }}</td>
                <td>{{ $receipt->status }}</td>
            </tr>
        </table>
    </div>

    @if ($receipt->status === 'Disetujui')
    <div class="section">
        <p><strong>Terima kasih atas pembayaran Anda.</strong></p>
    </div>
    @endif

    <div class="section" style="margin-top: 40px;">
        <p>Hormat kami,</p>
        {{-- <p><strong>{{ config('app.name') }}</strong></p> --}}
    </div>
</div>
</body>
</html>
