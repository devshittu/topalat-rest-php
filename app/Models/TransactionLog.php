<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends BaseModel
{
    use HasFactory;

    /**
     * @var mixed
     */
    protected $casts = [
        'payment_status' => 'integer',
        'service_render_status' => 'integer',
        'service_request_payload_data' => 'array',
    ];
    private $username;
//    protected $fillable = [‘email’, ‘reference’, ‘description’, 'payment_status', 'service_render_status', ];
    protected $guarded = ['service_request_payload_data'];


    public function retries()
    {
        return $this->hasMany(TransactionRetryLog::class);
    }
}
