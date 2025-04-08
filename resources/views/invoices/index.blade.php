@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Invoices</h5>
                        <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Create Invoice
                        </a>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 px-4">
                        <div class="table-responsive p-0">
                            <table class="table table-striped align-items-center mb-0 overflow-hidden"
                                id="dataTableInvoice">
                                <thead>
                                    <tr>
                                        <th>Invoice Number</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoices as $invoice)
                                        <tr>
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
                                                    class="badge bg-{{ $invoice->payment_status == 'Lunas' ? 'success' : 'danger' }}">
                                                    {{ $invoice->payment_status }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('invoices.show', $invoice->id) }}"
                                                    class="btn btn-info btn-sm">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('invoices.edit', $invoice->id) }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
@push('js')
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.bootstrap5.js"></script>

    <script>
        new DataTable('#dataTableInvoice');
    </script>
@endpush
