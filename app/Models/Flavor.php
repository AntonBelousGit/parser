<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flavor extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $keyType = 'string';

    protected $fillable = ['id','name'];

}
