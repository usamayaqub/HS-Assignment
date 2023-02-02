<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_dp extends Model
{
    use HasFactory;
    
    public function userdp()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}
