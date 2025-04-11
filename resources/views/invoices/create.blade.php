@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Create New Invoice</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('invoices.store') }}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Draft">Draft</option>
                                        <option value="Menunggu Approval">Menunggu Approval</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-6 mb-3">
                                    <label class="form-label">Payment Status</label>
                                    <select name="payment_status" class="form-select" required>
                                        <option value="Pending">Belum Dibayar</option>
                                        <option value="Partial">Pembayaran Sebagian</option>
                                        <option value="Complete">Lunas</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card mt-4">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Invoice Items</h6>
                                    <button type="button" class="btn btn-sm btn-success" id="addItem">
                                        <i class="fas fa-plus"></i> Add Item
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div id="itemsContainer">
                                        <div class="row item-row gx-2 gy-2 mb-3">
                                            <div class="col-12 col-md-4">
                                                <input type="text" name="items[0][item_name]" class="form-control"
                                                    placeholder="Item Name" required>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <input type="number" name="items[0][quantity]"
                                                    class="form-control quantity" placeholder="Quantity" required>
                                            </div>
                                            <div class="col-6 col-md-3">
                                                <input type="number" step="0.01" name="items[0][price_per_item]"
                                                    class="form-control price" placeholder="Price per Item" required>
                                            </div>
                                            <div class="col-6 col-md-2">
                                                <input type="number" step="0.01" name="items[0][total_price]"
                                                    class="form-control total" placeholder="Total" readonly>
                                            </div>
                                            <div class="col-6 col-md-1 d-flex align-items-start">
                                                <button type="button" class="btn btn-danger btn-sm remove-item mt-1">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">Create Invoice</button>
                                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
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
            let itemCount = 0;

            document.getElementById('addItem').addEventListener('click', function() {
                itemCount++;
                const newItem = `<div class="row item-row mb-3">
            <div class="col-md-4">
                <input type="text" name="items[${itemCount}][item_name]" class="form-control" placeholder="Item Name" required>
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity" placeholder="Quantity" required>
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="items[${itemCount}][price_per_item]" class="form-control price" placeholder="Price per Item" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="items[${itemCount}][total_price]" class="form-control total" placeholder="Total" readonly>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>`;
                document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', newItem);
            });

            document.getElementById('itemsContainer').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('.item-row').remove();
                }
            });

            document.getElementById('itemsContainer').addEventListener('input', function(e) {
                if (e.target.classList.contains('quantity') || e.target.classList.contains('price')) {
                    const row = e.target.closest('.item-row');
                    const quantity = row.querySelector('.quantity').value;
                    const price = row.querySelector('.price').value;
                    const total = row.querySelector('.total');
                    total.value = (quantity * price).toFixed(2);
                }
            });
        </script>
    @endpush