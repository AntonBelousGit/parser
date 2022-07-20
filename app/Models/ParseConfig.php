<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParseConfig extends Model
{
    use HasFactory;

    protected $fillable = ['name','enable','parser','connection','url'];
}
