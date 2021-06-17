<?php

namespace App\Traits;

use Exception;
use Ramsey\Uuid\Uuid as RamseyUuid;

trait Uuid
{
    /**
     * The UUID version to use.
     *
     * @return int
     */
    protected function uuidVersion(): int
    {
        return 4;
    }

    /**
     * The "booting" method of the model.
     */
    public static function bootUuid(): void
    {
        static::creating(function (self $model): void {
            foreach ($model->uuidFields as $field) {
                if (empty($model->{$field})) {
                    $model->{$field} = $model->generateUuid();
                }
            }
        });
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function generateUuid(): string
    {
        switch ($this->uuidVersion()) {
            case 1:
                return RamseyUuid::uuid1()->toString();
            case 4:
                return RamseyUuid::uuid4()->toString();
        }

        throw new Exception("UUID version [{$this->uuidVersion()}] not supported.");
    }
}
