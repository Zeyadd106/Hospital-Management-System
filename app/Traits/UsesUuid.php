<?php

namespace App\Traits;

use Illuminate\Support\Str;

/**
 * Trait UsesUuid
 * 
 * Provides UUID generation functionality for models.
 */
trait UsesUuid
{
    /**
     * Boot the trait, adding a creating observer.
     *
     * When creating a new model, we'll generate a UUID and assign it to the primary key.
     *
     * @return void
     */
    protected static function bootUsesUuid()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }
}
