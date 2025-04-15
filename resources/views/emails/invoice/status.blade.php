<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perubahan Status Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #f8f9fa;
            padding: 15px;
            border-bottom: 3px solid #0d6efd;
            margin-bottom: 20px;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #6c757d;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-approved {
            color: #198754;
            font-weight: bold;
        }
        .status-rejected {
            color: #dc3545;
            font-weight: bold;
        }
        .status-changed {
            color: #0d6efd;
            font-weight: bold;
        }
        .qrcode {
            text-align: center;
            margin: 20px 0;
        }
        .qrcode img {
            max-width: 200px;
        }
        .reason {
            background-color: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #0d6efd;
            margin: 15px 0;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #fff;
            background-color: #0d6efd;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 15px;
        }
        .button-success {
            background-color: #198754;
        }
        .button-danger {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Perubahan Status Invoice</h2>
    </div>
    
    <div class="content">
        <p>Halo,</p>
        
        @if($status == 'approved')
            <p>Invoice <strong>#{{ $invoice->invoice_number }}</strong> telah <span class="status-approved">DISETUJUI</span>.</p>
        @elseif($status == 'rejected')
            <p>Invoice <strong>#{{ $invoice->invoice_number }}</strong> telah <span class="status-rejected">DITOLAK</span>.</p>
        @else
            <p>Status Invoice <strong>#{{ $invoice->invoice_number }}</strong> telah <span class="status-changed">BERUBAH</span>.</p>
        @endif
        
        @if($actor)
            <p>Diproses oleh: {{ $actor->username }}</p>
        @endif
        
        <h3>Detail Invoice</h3>
        <table>
            <tr>
                <th>No. Invoice</th>
                <td>#{{ $invoice->invoice_number }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $invoice->description ?: 'Tidak ada deskripsi' }}</td>
            </tr>
            <tr>
                <th>Total</th>
                <td>Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $invoice->status }}</td>
            </tr>
            <tr>
                <th>Status Pembayaran</th>
                <td>{{ $invoice->payment_status }}</td>
            </tr>
        </table>
        
        <h3>Detail Item</h3>
        <table>
            <thead>
                <tr>
                    <th>Nama Item</th>
                    <th>Jumlah</th>
                    <th>Harga per Item</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->item_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp {{ number_format($item->price_per_item, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($status == 'approved')
            <div class="reason">
                <strong>Alasan Persetujuan:</strong>
                <p>{{ $invoice->draft_data['approval_reason'] ?? 'Tidak ada alasan yang diberikan.' }}</p>
            </div>
            
            <div class="qrcode">
                <p>Scan QR Code untuk mengunduh invoice:</p>
                <img src="{{ $qrCodeUrl }}" alt="QR Code">
            </div>
            
            <div style="text-align: center;">
                <a href="{{ route('invoices.download', $invoice->id) }}" class="button button-success">Download Invoice</a>
            </div>
        @endif
        
        @if($status == 'rejected')
            <div class="reason">
                <strong>Alasan Penolakan:</strong>
                <p>{{ $invoice->draft_data['rejection_reason'] ?? 'Tidak ada alasan yang diberikan.' }}</p>
            </div>
            
            <p>Silakan login ke sistem untuk melakukan revisi dan mengajukan kembali.</p>
            
            <div style="text-align: center;">
                <a href="{{ route('invoices.index') }}" class="button">Login ke Sistem</a>
            </div>
        @endif
    </div>
    
    <div class="footer">
        <p>Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }}</p>
    </div>
</body>
</html>