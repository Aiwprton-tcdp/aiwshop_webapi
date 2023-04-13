<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'good_id',
        'user_id',
    ];

    protected $hidden = [
    ];
    
    protected $table = 'carts';

    public function good()
    {
        return $this->belongsTo(Good::class, 'good_id');
    }
}
