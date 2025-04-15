@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Edit Invoice</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('invoices.update', $invoice) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <label>No. Invoice</label>
                                    <input type="text" class="form-control" value="{{ $invoice->invoice_number }}"
                                        readonly>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label>Status</label>
                                    <input type="text" class="form-control" value="{{ $invoice->status }}" readonly
                                        disabled>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <label>Payment Status</label>
                                    <input type="text" class="form-control" value="{{ $invoice->payment_status }}"
                                        readonly disabled>
                                </div>
                            </div>
                            <!-- Tambahkan checkbox untuk mengubah status disini -->
                            @if ($invoice->status === 'Ditolak')
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
                            <input type="hidden" name="status" value="{{ $invoice->status }}">
                            <input type="hidden" name="payment_status" value="{{ $invoice->payment_status }}">

                            <div class="mb-3">
                                <label>Total</label>
                                <input type="text" id="totalAmountDisplay" class="form-control"
                                    value="Rp {{ number_format($invoice->amount, 0, ',', '.') }}" readonly>
                                <input type="hidden" name="amount" id="invoiceAmount" value="{{ $invoice->amount }}">
                            </div>

                            {{-- Editable Items --}}
                            <div class="card mt-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Invoice Items</h6>
                                    <button type="button" class="btn btn-sm btn-success" id="addItem">
                                        <i class="fas fa-plus"></i> Tambah Item
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="itemsContainer">
                                        @foreach ($invoice->items as $index => $item)
                                            <div class="row item-row gx-2 gy-2 mb-3">
                                                <div class="col-12 col-md-4">
                                                    <input type="text" name="items[{{ $index }}][item_name]"
                                                        class="form-control" value="{{ $item->item_name }}" required>
                                                </div>
                                                <div class="col-6 col-md-2">
                                                    <input type="number" name="items[{{ $index }}][quantity]"
                                                        class="form-control quantity" value="{{ $item->quantity }}"
                                                        required>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <input type="number" step="0.01"
                                                        name="items[{{ $index }}][price_per_item]"
                                                        class="form-control price" value="{{ $item->price_per_item }}"
                                                        required>
                                                </div>
                                                <div class="col-6 col-md-2">
                                                    <input type="number" step="0.01" class="form-control total"
                                                        value="{{ $item->total_price }}" readonly>
                                                </div>
                                                <div class="col-6 col-md-1 d-flex align-items-start">
                                                    <button type="button" class="btn btn-danger btn-sm remove-item mt-1">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Kembali</a>
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
@push('js')
    <script>
        let itemCount = {{ count($invoice->items) }};

        function updateTotalAmount() {
            let total = 0;
            document.querySelectorAll('.item-row').forEach(row => {
                const qty = parseFloat(row.querySelector('.quantity').value) || 0;
                const price = parseFloat(row.querySelector('.price').value) || 0;
                const totalField = row.querySelector('.total');
                const subtotal = qty * price;
                totalField.value = subtotal.toFixed(2);
                total += subtotal;
            });
            document.getElementById('invoiceAmount').value = total.toFixed(2);
            document.getElementById('totalAmountDisplay').value = 'Rp ' + total.toLocaleString('id-ID');
        }

        document.getElementById('addItem').addEventListener('click', function() {
            itemCount++;
            const html = `
        <div class="row item-row g-2 mb-3">
    <div class="col-12 col-sm-6 col-md-4">
        <input type="text" name="items[${itemCount}][item_name]" class="form-control" placeholder="Item Name" required>
    </div>
    <div class="col-6 col-sm-3 col-md-2">
        <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity" placeholder="Quantity" required>
    </div>
    <div class="col-6 col-sm-3 col-md-3">
        <input type="number" step="0.01" name="items[${itemCount}][price_per_item]" class="form-control price" placeholder="Price per Item" required>
    </div>
    <div class="col-6 col-sm-4 col-md-2">
        <input type="number" step="0.01" name="items[${itemCount}][total_price]" class="form-control total" placeholder="Total" readonly>
    </div>
    <div class="col-6 col-sm-2 col-md-1 d-flex align-items-center">
        <button type="button" class="btn btn-danger btn-sm remove-item">
            <i class="fas fa-trash"></i>
        </button>
    </div>
</div>`;
            document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', html);
        });

        document.getElementById('itemsContainer').addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                e.target.closest('.item-row').remove();
                updateTotalAmount();
            }
        });

        document.getElementById('itemsContainer').addEventListener('input', function(e) {
            if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
                updateTotalAmount();
            }
        });

        // Initial update on load
        updateTotalAmount();
    </script>
@endpush
