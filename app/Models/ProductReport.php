<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reporter_id',
        'product_id',
        'reason',
        'details',
        'status',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
