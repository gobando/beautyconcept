<?php
/*
 * File name: CurrencyCast.php
 * Last modified: 2022.02.02 at 19:14:22
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Casts;

use App\Models\Currency as CurrencyModel;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

/**
 * Class CurrencyCast
 * @package App\Casts
 */
class CurrencyCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): CurrencyModel
    {
        if (!empty($value)) {
            $decodedValue = json_decode($value, true);
            $currency = new CurrencyModel($decodedValue);
            array_push($currency->fillable, 'id');
            $currency->id = $decodedValue['id'];
            return $currency;
        }
        return new CurrencyModel();
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {

        if (!$value instanceof CurrencyModel) {
            throw new InvalidArgumentException('The given value is not an Currency instance.');
        }

        return ['currency' => json_encode([
            'id' => $value['id'],
            'name' => $value['name'],
            'symbol' => $value['symbol'],
            'code' => $value['code'],
            'decimal_digits' => $value['decimal_digits'],
            'rounding' => $value['rounding'],
        ])];
    }
}
