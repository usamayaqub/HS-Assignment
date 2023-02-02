<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class uploadfiles extends Model
{
    use HasFactory;

    protected $fillable =[
          
        'file1', 'file2','user_id'
    ];
}
