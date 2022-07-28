<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $keyType = 'string';

    protected $fillable = ['id', 'name', 'url', 'image', 'image_mobile'];

    protected $casts =
        [
            'image' => 'array',
            'image_mobile' => 'array',
        ];

    /**
     * @return BelongsToMany
     */
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'product_size_flavor');
    }

    /**
     * @return BelongsToMany
     */
    public function topping(): BelongsToMany
    {
        return $this->belongsToMany(Topping::class, 'topping_product');
    }

    /**
     * @return HasMany
     */
    public function attributeProduct(): HasMany
    {
        return $this->hasMany(Attribute::class, 'product_id');
    }
}
