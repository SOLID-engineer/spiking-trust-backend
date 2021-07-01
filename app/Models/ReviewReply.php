<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\ReviewReply
 *
 * @property int $id
 * @property string $uuid
 * @property int $review_id
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply query()
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ReviewReply whereUuid($value)
 * @mixin \Eloquent
 */
class ReviewReply extends Model
{
    use HasFactory, Uuid;

    protected $table = 'review_replies';

    protected $uuidFields = ['uuid'];
}
