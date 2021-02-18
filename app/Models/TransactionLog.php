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
//    protected $fillable = [‘customer_email’, ‘reference’, ‘description’, ‘’, ‘’, ‘’, ‘’];
    protected $guarded = ['payment_status', 'service_render_status', 'service_request_payload_data'];
}
