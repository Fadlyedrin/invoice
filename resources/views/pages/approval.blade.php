@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-5">
                    <div class="card-header bg-secondary ">
                        <h4 class="mb-0 text-white">Invoice Perlu Disetujui</h4>
                    </div>
                    <div class="card-body">
                        @if ($approvedInvoices->isEmpty())
                            <p>Tidak ada invoice yang disetujui.</p>
                        @else
                            <table class="table table-bordered table-striped" id="dataTableInvoiceDraft">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Invoice Number</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($approvedInvoices as $invoice)
                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($invoice->creator)
                                                    {{ $invoice->creator->username }}
                                                @else
                                                    Unknown User
                                                @endif
                                            </td>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>Rp.{{ number_format($invoice->amount, 2) }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $invoice->status == 'Disetujui' ? 'success' : ($invoice->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                                    {{ $invoice->status }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $invoice->payment_status == 'Pending' ? 'warning' : ($invoice->payment_status == 'Partial' ? 'info' : ($invoice->payment_status == 'Complete' ? 'success' : 'secondary')) }}">
                                                    {{ $invoice->payment_status }}
                                                </span>

                                            </td>
                                            <td class="text-center">
                                                <div class="action-buttons d-inline-flex">
                                                    @can('approve invoices')
                                                        <a href="{{ route('invoices.show', $invoice->id) }}"
                                                            class="btn btn-info btn-sm me-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-secondary ">
                        <h4 class="mb-0 text-white">Receipt Perlu Disetujui</h4>
                    </div>
                    <div class="card-body">
                        @if ($approvedReceipts->isEmpty())
                            <p>Tidak ada receipt yang disetujui.</p>
                        @else
                            <table class="table table-bordered table-striped" id="dataTableReceiptDraft">
                                <thead class="table-light">
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Invoice</th>
                                        <th>Jumlah Dibayar</th>
                                        <th>Sisa Bayaran</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($approvedReceipts as $receipt)
                                        <tr class="text-center">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                @if ($receipt->invoice->creator)
                                                    {{ $receipt->invoice->creator->username }}
                                                @else
                                                    Unknown User
                                                @endif
                                            </td>
                                            <td>{{ $receipt->invoice->invoice_number }}</td>
                                            <td>Rp{{ number_format($receipt->amount_paid, 2, ',', '.') }}</td>
                                            <td>Rp{{ number_format($receipt->invoice->amount - $receipt->amount_paid, 2, ',', '.') }}
                                            </td>
                                            <td>{{ $receipt->payment_method }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $receipt->status == 'Disetujui' ? 'success' : ($receipt->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                                    {{ $receipt->status }}
                                                </span>
                                            </td>
                                            <td class="text-center ">
                                                <div class="action-buttons d-inline-flex">
                                                    <a href="{{ route('receipts.show', $receipt->id) }}"
                                                        class="btn btn-sm btn-info me-1" title="Lihat">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>

    <script>
        new DataTable('#dataTableReceiptDraft');
        new DataTable('#dataTableInvoiceDraft');
    </script>
@endpush