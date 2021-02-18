<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends BaseModel
{
    use HasFactory;
    protected $fillable = ['message', 'phone', 'subject', 'full_name', 'email'];
}
