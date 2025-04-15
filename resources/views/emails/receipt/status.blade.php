<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perubahan Status Receipt</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h2>Perubahan Status Receipt</h2>
    </div>
    
    <div class="content">
        <p>Halo,</p>
        
        @if($status == 'approved')
            <p>Receipt untuk Invoice <strong>#{{ $receipt->invoice->invoice_number }}</strong> telah <span class="status-approved">DISETUJUI</span>.</p>
        @elseif($status == 'rejected')
            <p>Receipt untuk Invoice <strong>#{{ $receipt->invoice->invoice_number }}</strong> telah <span class="status-rejected">DITOLAK</span>.</p>
        @else
            <p>Status Receipt untuk Invoice <strong>#{{ $receipt->invoice->invoice_number }}</strong> telah <span class="status-changed">BERUBAH</span>.</p>
        @endif
        
        @if($actor)
            <p>Diproses oleh: {{ $actor->name }}</p>
        @endif
        
        <h3>Detail Receipt</h3>
        <table>
            <tr>
                <th>Invoice</th>
                <td>#{{ $receipt->invoice->invoice_number }}</td>
            </tr>
            <tr>
                <th>Jumlah Pembayaran</th>
                <td>Rp {{ number_format($receipt->amount_paid, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Metode Pembayaran</th>
                <td>{{ $receipt->payment_method }}</td>
            </tr>
            <tr>
                <th>Tanggal Pembayaran</th>
                <td>{{ date('d F Y', strtotime($receipt->payment_date)) }}</td>
            </tr>
            <tr>
                <th>Status</th>
                <td>{{ $receipt->status }}</td>
            </tr>
        </table>
        
        @if($status == 'Disetujui')
            <div class="reason">
                <strong>Alasan Persetujuan:</strong>
                <p>{{ $receipt->draft_data['approval_reason'] ?? 'Tidak ada alasan yang diberikan.' }}</p>
            </div>
            
            @if($qrCodeUrl)
                <div class="qrcode">
                    <p>Scan QR Code untuk mengunduh receipt:</p>
                    <img src="{{ $qrCodeUrl }}" alt="QR Code">
                </div>
                <p>Atau klik <a href="{{ route('receipts.download', $receipt->id) }}">di sini</a> untuk mengunduh receipt.</p>
            @endif
        @endif
        
        @if($status == 'Ditolak')
            <div class="reason">
                <strong>Alasan Penolakan:</strong>
                <p>{{ $receipt->draft_data['rejection_reason'] ?? 'Tidak ada alasan yang diberikan.' }}</p>
            </div>
            <p>Silakan login ke sistem untuk melakukan revisi dan mengajukan kembali.</p>
        @endif
    </div>
    
    <div class="footer">
        <p>Email ini dikirim otomatis oleh sistem. Mohon tidak membalas email ini.</p>
        <p>&copy; {{ date('Y') }} Sistem Manajemen Invoice & Receipt</p>
    </div>
</body>
</html>

