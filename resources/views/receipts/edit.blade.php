@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Edit Receipt</h4>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 mx-5">
                        <form action="{{ route('receipts.update', $receipt->id) }}" method="POST">
                            @csrf
                            @method('PUT')

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

                            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                            <a href="{{ route('receipts.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
