<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

/**
 * App\Models\Setting
 *
 * @property int $id
 * @property string $key
 * @property string|null $value
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereValue($value)
 * @mixin \Eloquent
 */
class Setting extends Model
{
    use HasFactory;

    CONST MAIL_SETTINGS="MAIL_SETTINGS";
    CONST MAIL_INVITATION="MAIL_INVITATION";

    const CONST_MAIL_SETTINGS = [
        'MAIL_HOST' => 'mail_server',
        'MAIL_PORT' => 'port',
        'MAIL_USERNAME' => 'username',
        'MAIL_PASSWORD' => 'password',
        'MAIL_ENCRYPTION' => 'encryption',
    ];

    public static function setMailConfigBeforeSend () {
        $mail_setting = self::where('type', self::MAIL_SETTINGS)->get();
        if ($mail_setting->isNotEmpty()) {
            $mail_setting = $mail_setting->keyBy('key');
        }

        if (!empty($mail_setting['MAIL_HOST']['value'])) {
            Config::set('mail.mailers.smtp.host', $mail_setting['MAIL_HOST']['value']);
        }
        if (!empty($mail_setting['MAIL_PORT']['value'])) {
            Config::set('mail.mailers.smtp.port', $mail_setting['MAIL_PORT']['value']);
        }
        if (!empty($mail_setting['MAIL_ENCRYPTION']['value'])) {
            Config::set('mail.mailers.smtp.encryption', $mail_setting['MAIL_ENCRYPTION']['value']);
        }
        if (!empty($mail_setting['MAIL_USERNAME']['value'])) {
            Config::set('mail.mailers.smtp.username', $mail_setting['MAIL_USERNAME']['value']);
        }
        if (!empty($mail_setting['MAIL_PASSWORD']['value'])) {
            Config::set('mail.mailers.smtp.password', $mail_setting['MAIL_PASSWORD']['value']);
        }
        return;
    }
}
