<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $fillable = ['total', 'discount', 'less', 'vat', 'payable', 'in_cash', 'due', 'user_id', 'customer_id','supplier_id'];

    function customer():BelongsTo{
        return $this->belongsTo(Customer::class);
    }
    function supplier():BelongsTo{
        return $this->belongsTo(Supplier::class);
    }
    function invoiceProducts():HasMany{
        return $this->hasMany(InvoiceProduct::class);
    }
}
