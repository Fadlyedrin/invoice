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
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Detail Receipt
                        </h4>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 px-4">
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
                            {{-- Form Approval --}}
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

                            {{-- Form Reject --}}
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
@endsection