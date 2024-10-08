<?php

namespace Jcrodsolutions\LaravelUserStamp\App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Schema;
use Cache;

trait UserStampTrait
{
    /*
    To use a specific field name in a model:
    protected static $active = 'activo';
    protected static $createdBy = 'creado_por';
    protected static $updatedBy = 'actualizado_por';
     */

    public function initializeUserStampTrait()
    {
        //$columns = Schema::getColumnListing($this->getTable());
        $tableName = $this->getTable();
        $cacheKey = 'user_stamp_' . $tableName;
        if (Schema::hasTable('cache')) {
            $columns = Cache::remember($cacheKey, now()->addHours(2), function () use ($tableName) {
                return Schema::getColumnListing($tableName);
            });
        } else {
            $columns = Schema::getColumnListing($tableName);
        }

        $strActive = property_exists(static::class, 'active') ? self::$active : config('user-stamp.active', 'active');
        $strCreadoPor = property_exists(static::class, 'createdBy') ? self::$createdBy : config('user-stamp.created_by', 'created_by');
        $strActualizadoPor = property_exists(static::class, 'updatedBy') ? self::$updatedBy : config('user-stamp.updated_by', 'updated_by');

        if (in_array($strActive, $columns)) {
            $this->fillable[] = $strActive;
        }
        if (in_array($strCreadoPor, $columns)) {
            $this->fillable[] = $strCreadoPor;
        }
        if (in_array($strActualizadoPor, $columns)) {
            $this->fillable[] = $strActualizadoPor;
        }
    }

    protected static function booted()
    {
        if (!auth()->check()) {
            return;
        }

        $strActive = config('user-stamp.active', 'active');
        $strCreadoPor = config('user-stamp.created_by', 'created_by');
        $strActualizadoPor = config('user-stamp.updated_by', 'updated_by');

        $active = property_exists(static::class, 'active') ? self::$active : $strActive;
        $creadoPor = property_exists(static::class, 'createdBy') ? self::$createdBy : $strCreadoPor;
        $actualizadoPor = property_exists(static::class, 'updatedBy') ? self::$updatedBy : $strActualizadoPor;

        static::creating(function ($model) use ($active, $creadoPor, $actualizadoPor) {
            $user_id = auth()->id();
            $columns = Schema::getColumnListing($model->getTable());

            if (in_array($creadoPor, $columns)) {
                // $model->fillable = array_merge($model->fillable, [$creadoPor]);
                $model->$creadoPor = $user_id;
            }
            if (in_array($actualizadoPor, $columns)) {
                // $model->fillable = array_merge($model->fillable, [$actualizadoPor]);
                $model->$actualizadoPor = $user_id;
            }
        });

        static::updating(function ($model) use ($active, $actualizadoPor) {
            $user_id = auth()->id();
            $columns = Schema::getColumnListing($model->getTable());
            if (in_array($actualizadoPor, $columns)) {
                // $model->fillable = array_merge($model->fillable, [$actualizadoPor]);
                $model->$actualizadoPor = $user_id;
            }
        });
    }

    /**
     * Define the relationship with the user model for the `created_by` field.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): belongsTo
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        return $this->belongsTo($userModelClass, 'created_by');
    }
    /**
     * Define the relationship with the user model for the `created_by` field.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): belongsTo
    {
        $userModelClass = config('auth.providers.users.model', \App\Models\User::class);
        return $this->belongsTo($userModelClass, 'updated_by');
    }
}
