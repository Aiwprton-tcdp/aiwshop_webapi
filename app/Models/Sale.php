<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'good',
        'buyer',
        'price',
        'discount',
        'is_returned',
    ];
    
    protected $table = 'sales';
    
    public function goods()
    {
        return $this->belongsTo(Good::class, 'good');
    }
    
    public function buyers()
    {
        return $this->belongsTo(User::class, 'buyer');
    }
}
