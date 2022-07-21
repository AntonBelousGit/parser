<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Size extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';
    protected $fillable = ['id','name'];

    /**
     * @return BelongsToMany
     */
    public function flavors(): BelongsToMany
    {
        return $this->belongsToMany(Flavor::class, 'product_size_flavor')->withPivot('price');
    }
}
