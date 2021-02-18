<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model {
    protected $dateFormat = 'U';

    protected $casts = [
        'updated_at' => 'integer',
        'created_at' => 'integer',
    ];
}
