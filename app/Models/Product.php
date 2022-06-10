<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = ['id', 'name', 'image', 'image_mobile'];

    protected $casts =
        [
            'image' => 'array',
            'image_mobile' => 'array',
        ];

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_size_flavor');
    }

    public function topping(): BelongsToMany
    {
        return $this->belongsToMany(Topping::class, 'topping_product');
    }


}
