<?php
/*
 * File name: AvailabilityHour.php
 * Last modified: 2022.02.02 at 21:21:31
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * Class AvailabilityHour
 * @package App\Models
 * @version January 16, 2021, 4:08 pm UTC
 *
 * @property Salon salon
 * @property string id
 * @property string day
 * @property string start_at
 * @property string end_at
 * @property string data
 * @property integer salon_id
 */
class AvailabilityHour extends Model
{

    use HasTranslations;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'day' => 'required|max:16',
        'start_at' => 'required|date_format:H\:i',
        'end_at' => 'required|date_format:H\:i|after:start_at',
        'data' => 'max:255',
        'salon_id' => 'required|exists:salons,id'
    ];
    public $translatable = [
        'data',
    ];
    public $timestamps = false;
    public $table = 'availability_hours';
    public $fillable = [
        'day',
        'start_at',
        'end_at',
        'data',
        'salon_id'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'day' => 'string',
        'start_at' => 'string',
        'end_at' => 'string',
        'data' => 'string',
        'salon_id' => 'integer'
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',

    ];
    protected $hidden = [
        "created_at",
        "updated_at",
    ];

    /**
     * @return array
     */
    public function getCustomFieldsAttribute(): array
    {
        $hasCustomField = in_array(static::class, setting('custom_field_models', []));
        if (!$hasCustomField) {
            return [];
        }
        $array = $this->customFieldsValues()
            ->join('custom_fields', 'custom_fields.id', '=', 'custom_field_values.custom_field_id')
            ->where('custom_fields.in_table', '=', true)
            ->get()->toArray();

        return convertToAssoc($array, 'name');
    }

    /**
     * @return MorphMany
     */
    public function customFieldsValues(): MorphMany
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * @return BelongsTo
     **/
    public function salon(): BelongsTo
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'id');
    }

}
