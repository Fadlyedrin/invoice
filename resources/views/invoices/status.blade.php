@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Update Invoice Status</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.updateStatus', $invoice->id) }}" method="POST">
            @csrf
            <div class="mb-3">
                <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
                <p><strong>Current Status:</strong> {{ $invoice->status }}</p>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">New Status</label>
                <select name="status" class="form-select" required>
                    <option value="Disetujui">Disetujui</option>
                    <option value="Ditolak">Ditolak</option>
                    <option value="Menunggu Approval">Menunggu Approval</option>
                </select>
            </div>
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Status</button>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
