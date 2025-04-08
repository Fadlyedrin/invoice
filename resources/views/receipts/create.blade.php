@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Create Receipts
                        </h4>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body px-0 pt-0 pb-2 mx-5">
                        <form action="{{ route('receipts.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="invoice_id" class="form-label">Invoice</label>
                                <select name="invoice_id" id="invoice_id" class="form-select" required>
                                    <option value="">-- Pilih Invoice --</option>
                                    @foreach ($invoices as $invoice)
                                        <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} -
                                            {{ $invoice->amount }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="amount_paid" class="form-label">Jumlah Dibayar</label>
                                <input type="number" step="0.01" name="amount_paid" id="amount_paid"
                                    class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-select" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="payment_status" class="form-label">Status Pembayaran Invoice</label>
                                <select name="payment_status" id="payment_status" class="form-select" required>
                                    <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>
                                        Pending
                                    </option>
                                    <option value="Partial" {{ old('payment_status') == 'Partial' ? 'selected' : '' }}>
                                        Partial
                                    </option>
                                    <option value="Complete" {{ old('payment_status') == 'Complete' ? 'selected' : '' }}>
                                        Complete</option>
                                </select>
                                @error('payment_status')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>


                            <div class="mb-3">
                                <label for="payment_date" class="form-label">Tanggal Pembayaran</label>
                                <input type="date" name="payment_date" id="payment_date" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="Draft">Draft</option>
                                    <option value="Menunggu Approval">Menunggu Approval</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Simpan Receipt</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
