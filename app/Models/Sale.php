<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\SalesEnum;

class Sale extends Model
{
    use HasFactory;

    protected $primaryKey = 'invoice';

    public $incrementing = false;

    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';

    protected $fillable = [
        'invoice',
        'product_id',
        'user_id',
        'quantity',
        'status'
    ];

    public function products()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
