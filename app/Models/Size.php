<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = ['id','name'];

    public function flavor()
    {
        return $this->belongsToMany(
            Flavor::class, 'product_size_flavor','size_id','flavor_id'
        );
    }
}
