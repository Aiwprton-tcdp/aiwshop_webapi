<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatFile extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'message_id',
        'name',
        'path',
        'size',
        'deleted_at',
    ];
    
    protected $hidden = [
        'updated_at',
        'created_at',
    ];
    
    protected $table = 'chat_files';
}
