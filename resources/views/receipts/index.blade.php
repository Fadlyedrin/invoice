@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-0 mb-md-0">Receipts</h4>
                        <a href="{{ route('receipts.create') }}" class="btn btn-primary mt-2 mt-md-0">Add Receipt</a>
                    </div>
                    <div class="card-body px-2 px-sm-5 pt-0 pb-2">
                        <!-- Mobile view - card style -->
                        <div class="d-block d-md-none px-1">
                            @foreach ($receipts as $receipt)
                                <div class="receipt-card mb-3 p-3 border rounded">
                                    <span class="text-xs text-secondary">{{ $loop->iteration }}</span>
                                    <div class="d-flex justify-content-between mb-2">
                                        @if ($receipt->invoice->creator)
                                            {{ $receipt->invoice->creator->username }}
                                        @else
                                            Unknown User
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>{{ $receipt->invoice->invoice_number }}</strong>
                                        <span
                                            class="fw-bold">Rp{{ number_format($receipt->amount_paid, 2, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>{{ $receipt->payment_method }}</span>
                                        <span
                                            class="badge bg-{{ $receipt->status == 'Disetujui' ? 'success' : ($receipt->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                            {{ $receipt->status }}
                                        </span>
                                    </div>
                                    <div class="d-flex justify-content-end mt-2">
                                        <div class="action-buttons">
                                            <a href="{{ route('receipts.show', $receipt->id) }}"
                                                class="btn btn-info btn-md me-2" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('receipts.edit', $receipt->id) }}"
                                                class="btn btn-warning btn-md me-2" title="Edit">
                                                <i class="fas fa-pencil-square"></i>
                                            </a>
                                            <form action="{{ route('receipts.destroy', $receipt->id) }}" method="POST"
                                                class="d-inline"
                                                onsubmit="return confirm('Yakin ingin menghapus receipt ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-md" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop view - table -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-striped align-items-center mb-0" id="dataTableReceipt">
                                <thead>
                                    <tr class="text-center">
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Invoice</th>
                                        <th>Jumlah Dibayar</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receipts as $receipt)
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
                                                    <a href="{{ route('receipts.edit', $receipt->id) }}"
                                                        class="btn btn-sm btn-warning me-1" title="Edit">
                                                        <i class="fas fa-pencil-square"></i>
                                                    </a>
                                                    <form action="{{ route('receipts.destroy', $receipt->id) }}"
                                                        method="POST" class="d-inline"
                                                        onsubmit="return confirm('Yakin ingin menghapus receipt ini?')">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    <style>
        /* Custom styles for mobile view */
        .receipt-card {
            background-color: #f8f9fa;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .action-buttons {
            display: flex;
            align-items: center;
        }

        .action-buttons form {
            margin-bottom: 0;
        }

        @media (max-width: 767px) {
            .container-fluid {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .row.mx-1 {
                margin-left: 0 !important;
                margin-right: 0 !important;
            }

            .card-body {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            .receipt-card {
                margin-left: 0;
                margin-right: 0;
                width: 100%;
            }

            .card-header {
                padding: 1rem;
                text-align: center;
            }

            .btn-sm {
                padding: 0.35rem 0.65rem;
                font-size: 0.875rem;
            }

            /* Add extra margin to buttons on mobile */
            .action-buttons .btn {
                margin-right: 8px;
            }

            .action-buttons .btn:last-child {
                margin-right: 0;
            }
        }
    </style>
@endpush

@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>

    <script>
        new DataTable('#dataTableReceipt');
    </script>
@endpush
