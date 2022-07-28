<?php

namespace App\Models;

use App\Traits\Historical\Historical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use HasFactory,Historical;

    protected $guarded = [];

    protected $table = 'product_size_flavor';

    /**
     * @return HasMany
     */
    public function size(): HasMany
    {
        return $this->hasMany(Size::class, 'id', 'sizes_id');
    }

    /**
     * @return string[]
     */
    public function ignoreHistoryColumns(): array
    {
        return [
            'updated_at',
            'flavor_id',
            'size_id',
            'topping_id',
            'product_id'
        ];
    }
}
