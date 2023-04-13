<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'users_social_id',
        'token',
        'created_at',
    ];
    
    protected $hidden = [];
    
    const UPDATED_AT = null;

    protected $table = 'password_resets';
}
