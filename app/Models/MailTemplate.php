<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\MailTemplate
 *
 * @property int $id
 * @property string $uuid
 * @property string $title
 * @property string $content
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate query()
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereUuid($value)
 * @mixin \Eloquent
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|MailTemplate whereName($value)
 */
class MailTemplate extends Model
{
    const TYPE_SERVICE_REVIEW_INVITATION = 'service_review_invitation';
    const TYPE_PRODUCT_REVIEW_INVITATION = 'product_review_invitation';
    const TYPE_CLAIM_VERIFICATION = 'claim_verification';
    use HasFactory, Uuid;
    protected $uuidFields = ['uuid'];
    public $table = 'mail_templates';
}
