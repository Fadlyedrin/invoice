<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'description',
        'amount',
        'status',
        'payment_status',
        'draft_data',
        'created_by'
    ];

    protected $casts = [
        'draft_data' => 'array'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class)->withTrashed();
    }

    public function creator()
{
    return $this->belongsTo(User::class, 'created_by');
}

}
