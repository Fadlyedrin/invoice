@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
                        <h4 class="mb-2 mb-sm-0">Create Receipts</h4>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger mx-3 mx-sm-5">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="card-body px-3 px-sm-5 pt-0 pb-2">
                        <form action="{{ route('receipts.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="invoice_id" class="form-label">Invoice</label>
                                <select name="invoice_id" id="invoice_id" class="form-select" required>
                                    <option value="">-- Pilih Invoice --</option>
                                    @foreach ($invoices as $invoice)
                                        <option value="{{ $invoice->id }}">{{ $invoice->invoice_number }} - {{ $invoice->amount }}</option>
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
                                    <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Partial" {{ old('payment_status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="Complete" {{ old('payment_status') == 'Complete' ? 'selected' : '' }}>Complete</option>
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

                            <div class="mb-3 d-grid d-sm-block">
                                <button type="submit" class="btn btn-primary w-100 w-sm-auto">Simpan Receipt</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const invoiceSelect = document.getElementById('invoice_id');
        const amountInput = document.getElementById('amount_paid');

        // Data invoice amount (ambil dari Blade, dikasih attribute tambahan)
        const invoices = @json($invoices->pluck('amount', 'id'));

        invoiceSelect.addEventListener('change', function () {
            const selectedInvoiceId = this.value;
            const maxAmount = invoices[selectedInvoiceId] || 0;

            if (maxAmount > 0) {
                amountInput.max = maxAmount;
            } else {
                amountInput.removeAttribute('max');
            }
        });
    });
</script>
@endpush
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
