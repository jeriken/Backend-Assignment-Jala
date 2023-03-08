<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice';

    public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';

    protected $fillable = [
        'invoice',
        'product_id',
        'quantity',
        'harga'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
