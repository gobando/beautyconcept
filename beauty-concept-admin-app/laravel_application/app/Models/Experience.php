<?php
/*
 * File name: Experience.php
 * Last modified: 2022.02.02 at 21:21:32
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Models;

use App\Traits\HasTranslations;
use Eloquent as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Experience
 * @package App\Models
 * @version January 12, 2021, 11:16 am UTC
 *
 * @property Salon salon
 * @property string id
 * @property string title
 * @property string description
 * @property integer salon_id
 */
class Experience extends Model
{

    use HasTranslations;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'title' => 'required|max:127',
        'description' => 'required',
        'salon_id' => 'required|exists:salons,id'
    ];
    public $translatable = [
        'title',
        'description',
    ];
    public $table = 'experiences';
    public $fillable = [
        'title',
        'description',
        'salon_id'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'title' => 'string',
        'description' => 'string',
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

    public function getCustomFieldsAttribute()
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

    public function customFieldsValues()
    {
        return $this->morphMany('App\Models\CustomFieldValue', 'customizable');
    }

    /**
     * @return BelongsTo
     **/
    public function salon()
    {
        return $this->belongsTo(Salon::class, 'salon_id', 'id');
    }

}
