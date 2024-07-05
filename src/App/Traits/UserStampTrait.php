<?php

namespace Jcrodsolutions\LaravelUserStamp\App\Traits;

use Illuminate\Support\Facades\Schema;

trait UserStampTrait
{
    /*
    To use a specific field name in a model:
    protected static $createdBy = 'creado_por';
    protected static $updatedBy = 'actualizado_por';
     */
    protected static function booted()
    {
        if (!auth()->check()) {
            return;
        }

        $strCreadoPor = config('user-stamp.created_by','created_by');
        $strActualizadoPor = config('user-stamp.updated_by','updated_by');

        $creadoPor = property_exists(static::class, 'createdBy') ? self::$createdBy : $strCreadoPor;
        $actualizadoPor = property_exists(static::class, 'updatedBy') ? self::$updatedBy : $strActualizadoPor;

        static::creating(function ($model) use ($creadoPor, $actualizadoPor) {
            $user_id = auth()->id();
            if (Schema::hasColumn($model->getTable(), $creadoPor)) {
                $model->$creadoPor = $user_id;
            }
            if (Schema::hasColumn($model->getTable(), $actualizadoPor)) {
                $model->$actualizadoPor = $user_id;
            }
        });

        static::updating(function ($model) use ($actualizadoPor) {
            $user_id = auth()->id();
            if (Schema::hasColumn($model->getTable(), $actualizadoPor)) {
                $model->$actualizadoPor = $user_id;
            }
        });
    }
}
