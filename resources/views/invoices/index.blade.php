@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')

    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0 mb-md-0">Invoices</h5>
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary mt-2 mt-md-0">
                            <i class="fas fa-plus"></i> Create Invoice
                        </a>
                    </div>
                    <div id="alert">
                        @include('components.alert')
                    </div>
                    <div class="card-body px-2 px-sm-5 pt-0 pb-2">
                        <!-- Mobile view - card style -->
                        <div class="d-block d-md-none px-1">
                            @foreach ($invoices as $invoice)
                                <div class="invoice-card mb-3 p-3 border rounded">
                                    <span class="text-xs text-secondary">{{ $loop->iteration }}</span>
                                    <div class="d-flex justify-content-between mb-2">
                                        @if ($invoice->creator)
                                            {{ $invoice->creator->username }}
                                        @else
                                            Unknown User
                                        @endif
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <strong>{{ $invoice->invoice_number }}</strong>
                                        <span class="fw-bold">Rp.{{ number_format($invoice->amount, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span
                                            class="badge bg-{{ $invoice->status == 'Disetujui' ? 'success' : ($invoice->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                            {{ $invoice->status }}
                                        </span>
                                        <span
                                            class="badge bg-{{ $invoice->payment_status == 'Pending' ? 'warning' : ($invoice->payment_status == 'Partial' ? 'info' : ($invoice->payment_status == 'Complete' ? 'success' : 'secondary')) }}">
                                            {{ $invoice->payment_status }}
                                        </span>

                                    </div>
                                    <div class="d-flex justify-content-end mt-2">
                                        <div class="action-buttons">

                                                <a href="{{ route('invoices.show', $invoice->id) }}"
                                                    class="btn btn-info btn-md me-2">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                            @can('update invoices')
                                                <a href="{{ route('invoices.edit', $invoice->id) }}"
                                                    class="btn btn-warning btn-md me-2">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            @can('delete invoices')
                                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-md"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Desktop view - table -->
                        <div class="table-responsive d-none d-md-block">
                            <table class="table table-striped align-items-center mb-0" id="dataTableInvoice">
                                <thead>
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
                                    @foreach ($invoices as $invoice)
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
                                                    @can('update invoices')
                                                        <a href="{{ route('invoices.edit', $invoice->id) }}"
                                                            class="btn btn-warning btn-sm me-1">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endcan
                                                    @can('delete invoices')
                                                        <form action="{{ route('invoices.destroy', $invoice->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Are you sure?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endcan
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
        .invoice-card {
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

            .invoice-card {
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
        new DataTable('#dataTableInvoice');
    </script>
@endpush
