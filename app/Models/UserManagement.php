<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserManagement extends Model
{
    /** @use HasFactory<\Database\Factories\UserManagementFactory> */
    use HasFactory;

    protected $table = 'users';
    
    protected $guarded = [];
}
