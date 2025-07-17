<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdata extends Model
{
    /** @use HasFactory<\Database\Factories\RedeemFactory> */
    use HasFactory;

    protected $table = 'subdata';

    protected $guarded = [];    
}
