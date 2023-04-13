<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];
    
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
    
    protected $table = 'socials';

    public function users_data()
    {
        return $this->hasMany(UsersSocial::class)
            ->whereUserId(\Illuminate\Support\Facades\Auth::id());
    }
}
