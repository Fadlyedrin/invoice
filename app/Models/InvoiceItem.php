<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_name',
        'quantity',
        'price_per_item',
        'total_price',
        'item_details'
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'item_details' => 'array'
    ];

public function invoice()
{
    return $this->belongsTo(Invoice::class);
}


}