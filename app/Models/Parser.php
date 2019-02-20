<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parser extends Model
{
    protected $fillable = [
        'header', 'iframe','img', 'description',
    ];

}
