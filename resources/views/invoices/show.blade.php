@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Detail Invoice #{{ $invoice->invoice_number }}</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p><strong>Status:</strong> {{ $invoice->status }}</p>
                            <p><strong>Payment Status:</strong> {{ $invoice->payment_status }}</p>
                            <p><strong>Description:</strong> {{ $invoice->description }}</p>
                            <p><strong>Amount:</strong> Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>
                        </div>

                        <hr>

                        <div class="mb-4">
                            <h5 class="mb-3">Items</h5>
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <thead class="table-light">
                                        <tr class="text-center">
                                            <th>Item</th>
                                            <th>Qty</th>
                                            <th>Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice->items as $item)
                                            <tr class="text-center">
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>Rp {{ number_format($item->price_per_item, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if (in_array($invoice->status, ['Draft', 'Menunggu Approval']))
                            <div class="row">
                                <div class="col-12 col-md-6 mb-4">
                                    <form action="{{ route('invoices.approve', $invoice->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="reason" class="form-label">Approval Reason</label>
                                            <textarea name="reason" class="form-control" rows="3" required minlength="10"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">Approve</button>
                                    </form>
                                </div>
                                <div class="col-12 col-md-6 mb-4">
                                    <form action="{{ route('invoices.reject', $invoice->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="reason" class="form-label">Rejection Reason</label>
                                            <textarea name="reason" class="form-control" rows="3" required minlength="10"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css')
    <style>
        @media (max-width: 767px) {
            .container-fluid {
                padding-left: 0.1rem;
                padding-right: 0.1rem;
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
