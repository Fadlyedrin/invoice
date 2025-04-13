@component('mail::message')
# Notifikasi Perubahan Status Invoice

Invoice: *{{ $invoice->invoice_number }}*  
Status saat ini: *{{ $invoice->status }}*

@isset($invoice->draft_data['approval_reason'])
**Alasan Disetujui**: {{ $invoice->draft_data['approval_reason'] }}
@endisset

@isset($invoice->draft_data['rejection_reason'])
**Alasan Ditolak**: {{ $invoice->draft_data['rejection_reason'] }}
@endisset

@isset($invoice->draft_data['approved_by'])
**Disetujui oleh:** {{ $invoice->draft_data['approved_by'] }}
@endisset

@component('mail::button', ['url' => route('invoices.show', $invoice->id)])
Lihat Invoice
@endcomponent

---

**Unduh PDF Invoice (Scan QR Code):**  
[<img src="{{ $qrCodeUrl }}" alt="QR Code" width="150">]({{ route('invoices.download', $invoice->id) }})

Terima kasih,<br>
@endcomponent
