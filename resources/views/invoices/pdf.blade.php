<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0 30px;
        }
        .header, .footer {
            text-align: center;
            margin-bottom: 20px;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f0f0f0;
        }
        .total {
            text-align: right;
            font-weight: bold;
        }
        .qr {
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Invoice</h2>
        <p><strong>#{{ $invoice->invoice_number }}</strong></p>
    </div>

    <div class="invoice-details">
        <p><strong>Tanggal:</strong> {{ $invoice->created_at->format('d M Y') }}</p>
        <p><strong>Status:</strong> {{ $invoice->status }}</p>
        <p><strong>Status Pembayaran:</strong> {{ $invoice->payment_status }}</p>
        @if($invoice->description)
            <p><strong>Deskripsi:</strong> {{ $invoice->description }}</p>
        @endif
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Nama Item</th>
                <th>Qty</th>
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

    <p class="total">Total: Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>

    <!-- Memaksa pemisahan halaman untuk halaman kedua -->
    <div style="page-break-before: always;"></div>

    <!-- Halaman Kedua: Tabel Detail Item dan Informasi Pengguna -->
    @foreach($invoice->items as $item)
        @php
            $details = json_decode($item->item_details, true);
        @endphp

        <h3>Nama Item: {{ $item->item_name }}</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left;">No.</th>
                    <th style="text-align: left;">NIK</th>
                    <th style="text-align: left;">Nama</th>
                    <th style="text-align: left;">Provinsi</th>
                    <th style="text-align: left;">Kota</th>
                    <th style="text-align: left;">Kecamatan</th>
                    <th style="text-align: left;">Jenis Kelamin</th>
                    <th style="text-align: left;">Tanggal Lahir</th>
                </tr>
            </thead>
            <tbody>
                @if(is_array($details) && count($details) > 0)
                    @foreach($details as $i => $detail)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $detail['nik'] ?? '-' }}</td>
                            <td>{{ $detail['nama'] ?? '-' }}</td>
                            <td>{{ $detail['provinsi'] ?? '-' }}</td>
                            <td>{{ $detail['kota'] ?? '-' }}</td>
                            <td>{{ $detail['kecamatan'] ?? '-' }}</td>
                            <td>{{ $detail['jenis_kelamin'] ?? '-' }}</td>
                            <td>{{ $detail['tanggal_lahir'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="8">Tidak ada data detail</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach

    <div class="footer">
        <p>Terima kasih telah menggunakan layanan kami</p>
    </div>

</body>
</html>
