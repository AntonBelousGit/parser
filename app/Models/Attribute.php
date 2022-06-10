<?php

namespace App\Models;

use App\History\Traits\Historical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasFactory,Historical;

    protected $guarded = [];
    protected $table = 'product_size_flavor';

    public function size()
    {
        return $this->hasMany(Size::class,'id','sizes_id');
    }

    public function ignoreHistoryColumns()
    {
        return [
            'updated_at',
            'flavor_id',
            'size_id',
            'product_id'
        ];
    }
}
