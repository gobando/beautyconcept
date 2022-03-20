<?php
/*
 * File name: Coupon.php
 * Last modified: 2022.02.12 at 02:17:43
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Models;

use App\Casts\CouponCast;
use App\Traits\HasTranslations;
use DateTime;
use Eloquent as Model;
use Illuminate\Contracts\Database\Eloquent\Castable;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;

/**
 * Class Coupon
 * @package App\Models
 *
 * @property integer id
 * @property string code
 * @property double discount
 * @property string discount_type
 * @property string description
 * @property DateTime expires_at
 * @property boolean enabled
 * @property Collection[] eServices
 * @property Collection[] salons
 * @property Collection[] categories
 * @property float|int $value
 */
class Coupon extends Model implements Castable
{

    use HasTranslations;

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'code' => 'required|unique:coupons|max:50',
        'discount' => 'required|numeric|min:0',
        'discount_type' => 'required',
        'expires_at' => 'required|date|after_or_equal:tomorrow'
    ];
    public $translatable = [
        'description',
    ];
    public $table = 'coupons';
    public $fillable = [
        'code',
        'discount',
        'discount_type',
        'description',
        'expires_at',
        'enabled'
    ];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'code' => 'string',
        'discount' => 'double',
        'value' => 'double',
        'discount_type' => 'string',
        'description' => 'string',
        'expires_at' => 'datetime',
        'enabled' => 'boolean'
    ];
    /**
     * New Attributes
     *
     * @var array
     */
    protected $appends = [
        'custom_fields',

    ];

    /**
     * @return CastsAttributes|CastsInboundAttributes|string
     */
    public static function castUsing()
    {
        return CouponCast::class;
    }

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

    public function discountables(): HasMany
    {
        return $this->hasMany(Discountable::class, 'coupon_id');
    }

    public function eServices(): MorphToMany
    {
        return $this->morphedByMany(EService::class, 'discountable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphedByMany(Category::class, 'discountable');
    }

    public function salons(): MorphToMany
    {
        return $this->morphedByMany(Salon::class, 'discountable');
    }

    public function getValue($eServices): Coupon
    {
        $couponValue = 0;
        $eServicesOfCategories = $this->categories->pluck('eServices')->flatten()->toArray();
        $eServicesOfSalons = $this->salons->pluck('eServices')->flatten()->toArray();
        $couponEServices = $this->eServices->concat($eServicesOfCategories)->concat($eServicesOfSalons);
        $couponEServicesIds = $couponEServices->pluck('id')->toArray();
        foreach ($eServices as $eService) {
            if (in_array($eService->id, $couponEServicesIds)) {
                if ($this->discount_type == 'percent') {
                    $couponValue += $eService->getPrice() * $this->discount / 100;
                } else {
                    $couponValue += $this->discount;
                }
            }
        }
        $this->value = $couponValue;
        unset($this['eServices'], $this['salons'], $this['categories']);
        return $this;
    }

}
