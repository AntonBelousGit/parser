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

    public function flavors()
    {
        return $this->belongsToMany(Flavor::class, 'product_size_flavor')->withPivot('price');
    }
}
