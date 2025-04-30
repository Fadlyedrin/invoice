{{-- @extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <h2>Detail Receipt</h2>
                <div class="card-body">
                    <p><strong>Invoice:</strong> {{ $receipt->invoice->invoice_number }}</p>
                    <p><strong>Amount Paid:</strong> Rp{{ number_format($receipt->amount_paid, 2) }}</p>
                    <p><strong>Payment Method:</strong> {{ $receipt->payment_method }}</p>
                    <p><strong>Status:</strong> <span
                            class="badge bg-{{ $receipt->status == 'Disetujui' ? 'success' : ($receipt->status == 'Ditolak' ? 'danger' : 'warning') }}">{{ $receipt->status }}</span>
                    </p>

                    @isset($receipt->draft_data['reason'])
                        <p><strong>Alasan Approval:</strong> {{ $receipt->draft_data['reason'] }}</p>
                    @endisset

                    @if (in_array($receipt->status, ['Draft', 'Menunggu Approval']))
                        <h5>Approval Receipt</h5>
                        <div class="row">
                            Form Approval
                            <div class="col-md-6">
                                <form action="{{ route('receipts.approve', $receipt->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="reason" class="form-label">Alasan Approval</label>
                                        <textarea name="reason" class="form-control" required minlength="10"
                                            placeholder="Contoh: Pembayaran sudah diterima..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100">Approve</button>
                                </form>
                            </div>

                            Form Reject
                            <div class="col-md-6">
                                <form action="{{ route('receipts.reject', $receipt->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="rejection_reason" class="form-label">Alasan Penolakan</label>
                                        <textarea name="rejection_reason" class="form-control" required minlength="10"
                                            placeholder="Contoh: Bukti pembayaran tidak valid..."></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-danger w-100">Reject</button>
                                </form>
                            </div>
                        </div>
                    @endif

                    <a href="{{ route('receipts.index') }}" class="btn btn-secondary mt-3">Kembali</a>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection --}}
@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4 class="mb-0">Detail Receipt</h4>
                    </div>
                    <div class="card-body px-3 px-sm-4 pt-0 pb-4">
                        <p><strong>Invoice:</strong> {{ $receipt->invoice->invoice_number }}</p>
                        <p><strong>Amount Paid:</strong> Rp{{ number_format($receipt->amount_paid, 2) }}</p>
                        <p><strong>Payment Method:</strong> {{ $receipt->payment_method }}</p>
                        <p><strong>Status:</strong>
                            <span
                                class="badge bg-{{ $receipt->status == 'Disetujui' ? 'success' : ($receipt->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                {{ $receipt->status }}
                            </span>
                        </p>

                        @isset($receipt->draft_data['reason'])
                            <p><strong>Alasan Approval:</strong> {{ $receipt->draft_data['reason'] }}</p>
                        @endisset

                        @if (in_array($receipt->status, ['Draft', 'Menunggu Approval']))
                            <h5 class="mt-4">Approval Receipt</h5>
                            <div class="row">
                                {{-- Form Approval --}}
                                <div class="col-12 col-md-6 mb-4">
                                    <form action="{{ route('receipts.approve', $receipt->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="reason" class="form-label">Alasan Approval</label>
                                            <textarea name="reason" class="form-control" required minlength="10"
                                                placeholder="Contoh: Pembayaran sudah diterima..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success w-100">Approve</button>
                                    </form>
                                </div>

                                {{-- Form Reject --}}
                                <div class="col-12 col-md-6 mb-4">
                                    <form action="{{ route('receipts.reject', $receipt->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="reason" class="form-label">Alasan Penolakan</label>
                                            <textarea name="reason" class="form-control" required minlength="10"
                                                placeholder="Contoh: Bukti pembayaran tidak valid..."></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger w-100">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('receipts.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
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