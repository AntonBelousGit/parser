<?php

namespace App\Models;

use App\History\Traits\Historical;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Attribute extends Model
{
    use HasFactory,Historical;

//    protected $fillable = ['product_id','size_id','flavor_id','price'];
    protected $guarded = [];

    protected $table = 'product_size_flavor';

//    public function size2()
//    {
//        return $this->hasManyThrough(
//            Size::class,
//            Flavor::class,
//            'size_id',
//            'flavor_id');
//    }

    public function size()
    {
        return $this->hasMany(Size::class,'id','sizes_id');
    }


    public function ignoreHistoryColumns()
    {
        return [
//            'updated_at',
//            'flavor_id',
//            'size_id',
//            'product_id'
        ];
    }
}
