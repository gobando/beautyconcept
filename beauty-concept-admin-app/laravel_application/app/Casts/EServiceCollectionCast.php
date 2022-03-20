<?php
/*
 * File name: EServiceCollectionCast.php
 * Last modified: 2022.02.15 at 13:49:23
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Casts;

use App\Models\EService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Class EServiceCollectionCast
 * @package App\Casts
 */
class EServiceCollectionCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): array
    {
        if (!empty($value)) {
            $decodedValue = json_decode($value, true);
            return array_map(function ($value) {
                $eService = EService::find($value['id']);
                if (!empty($eService)) {
                    return $eService;
                }
                $eService = new EService($value);
                array_push($eService->fillable, 'id');
                $eService->id = $value['id'];
                return $eService;
            }, $decodedValue);
        }
        return [];
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
//        if (!$value instanceof Collection) {
//            throw new InvalidArgumentException('The given value is not an Collection instance.');
//        }
        return [
            'e_services' => json_encode($value->map->only(['id', 'name', 'price', 'discount_price']))
        ];
    }
}
