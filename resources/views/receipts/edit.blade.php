@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-2 mb-sm-0">Edit Receipt</h4>
                    </div>

                    <div class="card-body px-3 px-sm-5 pt-0 pb-2">
                        <form action="{{ route('receipts.update', $receipt->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            @if ($receipt->status === 'Ditolak')
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="change_status"
                                                id="change_status" value="1">
                                            <label class="form-check-label" for="change_status">
                                                Ubah status menjadi "Menunggu Approval" untuk diajukan kembali
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="mb-3">
                                <label for="invoice_id" class="form-label">Pilih Invoice</label>
                                <select name="invoice_id" id="invoice_id" class="form-select" readonly disabled>
                                    <option value="">-- Pilih Invoice --</option>
                                    @foreach ($invoices as $invoice)
                                        <option value="{{ $invoice->id }}"
                                            {{ $receipt->invoice_id == $invoice->id ? 'selected' : '' }}>
                                            {{ $invoice->invoice_number }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('invoice_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="amount_paid" class="form-label">Jumlah Dibayar</label>
                                <input type="number" name="amount_paid" id="amount_paid" class="form-control"
                                    value="{{ old('amount_paid', $receipt->amount_paid) }}" required>
                                @error('amount_paid')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Status Pembayaran Invoice</label>
                                <select name="payment_status" id="payment_status" class="form-select" required>
                                    <option value="Pending"
                                        {{ $receipt->invoice->payment_status == 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Partial"
                                        {{ $receipt->invoice->payment_status == 'Partial' ? 'selected' : '' }}>Partial
                                    </option>
                                    <option value="Complete"
                                        {{ $receipt->invoice->payment_status == 'Complete' ? 'selected' : '' }}>Complete
                                    </option>
                                </select>
                                @error('payment_status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="Cash" {{ $receipt->payment_method == 'Cash' ? 'selected' : '' }}>Cash
                                    </option>
                                    <option value="Credit Card"
                                        {{ $receipt->payment_method == 'Credit Card' ? 'selected' : '' }}>Credit Card
                                    </option>
                                    <option value="Bank Transfer"
                                        {{ $receipt->payment_method == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer
                                    </option>
                                </select>
                                @error('payment_method')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control"
                                    value="{{ old('payment_date', $receipt->payment_date->format('Y-m-d')) }}" required>
                                @error('payment_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 d-grid gap-2 d-sm-flex justify-content-sm-start">
                                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                                <a href="{{ route('receipts.index') }}" class="btn btn-secondary">Batal</a>
                            </div>
                        </form>
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
