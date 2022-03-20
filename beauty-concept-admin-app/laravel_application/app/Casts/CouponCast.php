<?php
/*
 * File name: CouponCast.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Casts;

use App\Models\Coupon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

/**
 * Class CouponCast
 * @package App\Casts
 */
class CouponCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): Coupon
    {
        if (!empty($value)) {
            $decodedValue = json_decode($value, true);
            $coupon = new Coupon($decodedValue);
            array_push($coupon->fillable, 'id', 'value');
            $coupon->id = $decodedValue['id'];
            $coupon->value = $decodedValue['value'] ?? 0;
            return $coupon;
        }
        return new Coupon();
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
        if (!$value instanceof Coupon) {
            return ['coupon' => null];
        }

        return [
            'coupon' => json_encode(
                [
                    'id' => $value['id'],
                    'code' => $value['code'],
                    'discount' => $value['discount'],
                    'value' => $value['value'],
                    'discount_type' => $value['discount_type'],
                ]
            )
        ];
    }
}
