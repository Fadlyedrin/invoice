@extends('layouts.app', ['class' => 'g-sidenav-show bg-gray-100'])

@section('content')
    @include('layouts.navbars.auth.topnav')
    <div class="container-fluid py-4">
        <div class="row mt-4 mx-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header">
                        <h4>Detail Invoice #{{ $invoice->invoice_number }}</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Status:</strong> {{ $invoice->status }}</p>
                        <p><strong>Payment Status:</strong> {{ $invoice->payment_status }}</p>
                        <p><strong>Description:</strong> {{ $invoice->description }}</p>
                        <p><strong>Amount:</strong> Rp {{ number_format($invoice->amount, 0, ',', '.') }}</p>

                        <hr>

                        <h5>Items</h5>
                        <ul>
                            @foreach ($invoice->items as $item)
                                <li>{{ $item->item_name }} - {{ $item->quantity }} x {{ $item->price_per_item }} =
                                    {{ $item->total_price }}</li>
                            @endforeach
                        </ul>
                        @if (in_array($invoice->status, ['Draft', 'Menunggu Approval']))
                            <div class="row">
                                <div class="col-md-6">
                                    <form action="{{ route('invoices.approve', $invoice->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="reason" class="form-label">Approval Reason</label>
                                            <textarea name="reason" class="form-control" required minlength="10"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-success">Approve</button>
                                    </form>
                                </div>
                                <div class="col-md-6">
                                    <form action="{{ route('invoices.reject', $invoice->id) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="reason" class="form-label">Rejection Reason</label>
                                            <textarea name="reason" class="form-control" required minlength="10"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
