<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    const STATUS_QUEUED = 'queued';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_SENDING = 'sending';
    const STATUS_NOT_DELIVERED = 'not_delivered';
    const STATUS_DELIVERED = 'delivered';

    const TYPE_SERVICE_REVIEW = 'service_review';
    const TYPE_PRODUCT_REVIEW = 'product_review';

    use HasFactory;

}
