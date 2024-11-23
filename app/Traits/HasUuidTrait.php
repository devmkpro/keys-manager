<?php

namespace App\Traits;

trait HasUuidTrait
{
    protected static function bootHasUuidTrait()
    {
        static::creating(function ($model) {
            $model->uuid = (string) \Illuminate\Support\Str::uuid();
        });
    }
}
