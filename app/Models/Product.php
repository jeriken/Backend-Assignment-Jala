<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use BinaryCats\Sku\HasSku;

class Product extends Model
{
    use HasFactory, HasSku;

    protected $primaryKey = 'sku';

    public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sku',
        'nama',
        'harga',
        'stock'
    ];

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'product_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id');
    }
}
