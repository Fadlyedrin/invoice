@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Receipts
                                <a href="{{ route('receipts.create') }}" class="btn btn-primary float-end">Add Receipt</a>
                        </h4>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2 px-4">
                        <div class="table-responsive p-0">
                            <table class="table table-striped align-items-center mb-0 overflow-hidden" id="dataTableReceipt">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Jumlah Dibayar</th>
                                        <th>Metode</th>
                                        <th>Status</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($receipts as $receipt)
                                        <tr>
                                            <td>{{ $receipt->invoice->invoice_number }}</td>
                                            <td>Rp{{ number_format($receipt->amount_paid, 2, ',', '.') }}</td>
                                            <td>{{ $receipt->payment_method }}</td>

                                                                                        <td>
                                                <span
                                                    class="badge bg-{{ $receipt->status == 'Disetujui' ? 'success' : ($receipt->status == 'Ditolak' ? 'danger' : 'warning') }}">
                                                    {{ $receipt->status }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('receipts.show', $receipt->id) }}"
                                                    class="btn btn-sm btn-info" title="Lihat">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('receipts.edit', $receipt->id) }}"
                                                    class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-pencil-square"></i>
                                                </a>
                                                <form action="{{ route('receipts.destroy', $receipt->id) }}" method="POST"
                                                    class="d-inline"
                                                    onsubmit="return confirm('Yakin ingin menghapus receipt ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
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
        new DataTable('#dataTableReceipt');
    </script>
@endpush