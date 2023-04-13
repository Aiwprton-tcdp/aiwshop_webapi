<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersSocial extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'social_id',
        'value',
    ];
    
    protected $hidden = [
    ];
    
    protected $table = 'users_socials';
    
    public function social()
    {
        return $this->belongsTo(Social::class, 'social_id');
    }
}
