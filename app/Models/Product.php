<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'brand',
        'quantity',
        'price',
        'active'
    ];

    protected $dates = ['deleted_at'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

}
