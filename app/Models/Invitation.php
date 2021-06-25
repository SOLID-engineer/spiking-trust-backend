<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Invitation
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $reference_number
 * @property string $status
 * @property string $type
 * @property string $subject
 * @property string $body
 * @property string $sender_name
 * @property string $sender_email
 * @property string $reply_to_email
 * @property string $sent_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereReferenceNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereReplyToEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereSenderEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereSenderName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Invitation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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

    protected $fillable = [
        'template_id',
        'company_id',
        'name',
        'email',
        'reference_number',
        'type',
        'sender_name',
        'sender_email',
        'reply_to_email',
    ];

}
