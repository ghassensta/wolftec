<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'productName',
        'quantity',
        'price',
        'desc',
        'statut',
        'category_id',
    ];
    // Autres relations, accesseurs, mutateurs, etc.
}
