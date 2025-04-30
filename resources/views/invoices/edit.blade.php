@extends('layouts.app')

@section('content')

    <div class="container-fluid py-4">
        <div class="row mt-4 mx-1 mx-sm-4">
            <div class="col-12">
@if ($errors->any())
<div class="alert alert-danger mx-3 mx-sm-5">
    <ul class="mb-0">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@php
$oldItems = old('items', $invoice->items->map(function ($item) {
    return [
        'id' => $item->id,
        'item_name' => $item->item_name,
        'quantity' => $item->quantity,
        'price_per_item' => $item->price_per_item,
        'item_details' => is_string($item->item_details) ? $item->item_details : json_encode($item->item_details),
    ];
})->toArray());
@endphp

<div class="card mb-4">
    <div class="card-header">
        <h5>Edit Invoice</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label>No. Invoice</label>
                    <input type="text" class="form-control" value="{{ $invoice->invoice_number }}" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Status</label>
                    <input type="text" class="form-control" value="{{ $invoice->status }}" readonly>
                </div>
                <div class="col-md-4 mb-3">
                    <label>Payment Status</label>
                    <input type="text" class="form-control" value="{{ $invoice->payment_status }}" readonly>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6>Invoice Items</h6>
                    <button type="button" class="btn btn-success btn-sm" id="addItem">
                        <i class="fas fa-plus"></i> Tambah Item
                    </button>
                </div>
                <div class="card-body" id="itemsContainer">
                    @foreach($oldItems as $index => $item)
                    <div class="row item-row mb-3">
                        <div class="col-md-3">
                            <input type="text" name="items[{{ $index }}][item_name]" class="form-control" value="{{ $item['item_name'] }}" required>
                            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $item['id'] }}">
                            <input type="hidden" name="items[{{ $index }}][item_details]" class="item-details-input" value='{{ $item['item_details'] }}'>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="items[{{ $index }}][quantity]" class="form-control quantity" value="{{ $item['quantity'] }}" min="1" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" name="items[{{ $index }}][price_per_item]" class="form-control price" value="{{ $item['price_per_item'] }}" required>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-info btn-sm detail-nik" data-index="{{ $index }}">
                                <i class="fas fa-id-card"></i> Input NIK
                            </button>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-item">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endforeach
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

<!-- Modal NIK -->
<div class="modal fade" id="nikModal" tabindex="-1" aria-labelledby="nikModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input/Edit NIK</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="nik-modal-content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="saveNIK">Simpan NIK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    let itemCount = {{ count($oldItems) }};
    let currentNikItemIndex = 0;

    document.getElementById('addItem').addEventListener('click', function () {
        const html = `
        <div class="row item-row mb-3">
            <div class="col-md-3">
                <input type="text" name="items[${itemCount}][item_name]" class="form-control" placeholder="Nama Item" required>
                <input type="hidden" name="items[${itemCount}][item_details]" class="item-details-input" value="[]">
                <input type="hidden" name="items[${itemCount}][id]" value="">
            </div>
            <div class="col-md-2">
                <input type="number" name="items[${itemCount}][quantity]" class="form-control quantity" min="1" value="1" required>
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="items[${itemCount}][price_per_item]" class="form-control price" placeholder="Harga" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-info btn-sm detail-nik" data-index="${itemCount}">
                    <i class="fas fa-id-card"></i> Input NIK
                </button>
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger btn-sm remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>`;
        document.getElementById('itemsContainer').insertAdjacentHTML('beforeend', html);
        itemCount++;
    });

    document.getElementById('itemsContainer').addEventListener('click', function(e) {
        if (e.target.closest('.remove-item')) {
            e.target.closest('.item-row').remove();
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.detail-nik')) {
            const button = e.target.closest('.detail-nik');
            currentNikItemIndex = button.getAttribute('data-index');
            const row = button.closest('.item-row');
            const quantity = parseInt(row.querySelector('.quantity').value) || 1;
            const detailsInput = row.querySelector(`input[name="items[${currentNikItemIndex}][item_details]"]`);

            let existingNIKs = [];
            if (detailsInput.value) {
                try {
                    existingNIKs = JSON.parse(detailsInput.value);
                } catch (e) {
                    console.error("❌ Gagal parse item_details:", detailsInput.value, e);
                    existingNIKs = [];
                }
            }

            let modalContent = '';
            for (let i = 0; i < quantity; i++) {
                const currentNIK = (existingNIKs[i] && existingNIKs[i].nik) ? existingNIKs[i].nik : '';
                const currentNama = (existingNIKs[i] && existingNIKs[i].nama) ? existingNIKs[i].nama : '';

                modalContent += `
                <div class="mb-3">
                    <label class="form-label">Nama #${i+1}</label>
                    <input type="text" class="form-control nama-input" data-index="${i}" value="${currentNama}">
                </div>
                <div class="mb-3">
                    <label class="form-label">NIK #${i+1}</label>
                    <input type="text" class="form-control nik-input" data-index="${i}" value="${currentNIK}" oninput="previewNik(this, ${i})">
                    <small id="preview-nik-${i}" class="text-muted"></small>
                </div>`;
            }

            document.getElementById('nik-modal-content').innerHTML = modalContent;
            const nikModal = new bootstrap.Modal(document.getElementById('nikModal'));
            nikModal.show();
        }
    });

    document.getElementById('saveNIK').addEventListener('click', function () {
        const nikArray = [];

        const nikInputs = document.querySelectorAll('.nik-input');
        const namaInputs = document.querySelectorAll('.nama-input');

        nikInputs.forEach((input, i) => {
            const nik = input.value.trim();
            const nama = namaInputs[i] ? namaInputs[i].value.trim() : '';
            const previewText = document.getElementById(`preview-nik-${i}`).innerText;
            const [provinsi = '-', kota = '-', kecamatan = '-'] = previewText.split(' / ');

            if (nik) {
                nikArray.push({
                    nik: nik,
                    nama: nama,
                    provinsi: provinsi,
                    kota: kota,
                    kecamatan: kecamatan
                });
            }
        });

        const row = document.querySelector(`.detail-nik[data-index="${currentNikItemIndex}"]`).closest('.item-row');
        row.querySelector(`input[name="items[${currentNikItemIndex}][item_details]"]`).value = JSON.stringify(nikArray);

        bootstrap.Modal.getInstance(document.getElementById('nikModal')).hide();
    });

    function previewNik(input, index) {
        const nik = input.value;
        if (nik.length >= 6) {
            fetch(`/parse-nik/${nik}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`preview-nik-${index}`).innerText = `${data.provinsi} / ${data.kota} / ${data.kecamatan}`;
                    } else {
                        document.getElementById(`preview-nik-${index}`).innerText = 'NIK tidak valid';
                    }
                })
                .catch(() => {
                    document.getElementById(`preview-nik-${index}`).innerText = 'Error parsing';
                });
        } else {
            document.getElementById(`preview-nik-${index}`).innerText = '';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.item-details-input').forEach(function(input) {
            if (!input.value || input.value === 'null') {
                input.value = '[]';
            } else {
                try {
                    const parsed = JSON.parse(input.value);
                    input.value = JSON.stringify(parsed);
                } catch (e) {
                    input.value = '[]';
                }
            }
        });
    });
</script>
@endpush
