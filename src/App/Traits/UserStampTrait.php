<?php

namespace Jcrodsolutions\LaravelUserStamp\App\Traits;

use Illuminate\Support\Facades\Schema;

trait UserStampTrait
{
    /*
    To use a specific field name in a model:
    protected static $active = 'activo';
    protected static $createdBy = 'creado_por';
    protected static $updatedBy = 'actualizado_por';
     */
    protected static function booted()
    {
        if (!auth()->check()) {
            return;
        }

        $strActive = config('user-stamp.active','active');
        $strCreadoPor = config('user-stamp.created_by','created_by');
        $strActualizadoPor = config('user-stamp.updated_by','updated_by');

        $active = property_exists(static::class, 'active') ? self::$active : $strActive;
        $creadoPor = property_exists(static::class, 'createdBy') ? self::$createdBy : $strCreadoPor;
        $actualizadoPor = property_exists(static::class, 'updatedBy') ? self::$updatedBy : $strActualizadoPor;

        static::creating(function ($model) use ($active, $creadoPor, $actualizadoPor) {
            $user_id = auth()->id();
            $columns = Schema::getColumnListing($model->getTable());

            if (in_array($active,$columns)) {
                $model->fillable = array_merge($model->fillable, [$active]);
            }
            if (in_array($creadoPor,$columns)) {
                $model->fillable = array_merge($model->fillable, [$creadoPor]);
                $model->$creadoPor = $user_id;
            }
            if (in_array($actualizadoPor,$columns)) {
                $model->fillable = array_merge($model->fillable, [$actualizadoPor]);
                $model->$actualizadoPor = $user_id;
            }
        });

        static::updating(function ($model) use ($active, $actualizadoPor) {
            $user_id = auth()->id();
            $columns = Schema::getColumnListing($model->getTable());
            if (in_array($active,$columns)) {
                $model->fillable = array_merge($model->fillable, [$active]);
            }
            if (in_array($actualizadoPor,$columns)) {
                $model->fillable = array_merge($model->fillable, [$actualizadoPor]);
                $model->$actualizadoPor = $user_id;
            }
        });
    }
}
