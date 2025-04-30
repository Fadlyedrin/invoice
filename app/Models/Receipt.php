<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_id',
        'amount_paid',
        'payment_method',
        'status',
        'payment_date',
        'draft_data',
        'receipt_number'
    ];

    protected $casts = [
        'draft_data' => 'array',
        'payment_date' => 'date',
    ];

public function invoice()
{
       return $this->belongsTo(Invoice::class)->withTrashed();
}
}
