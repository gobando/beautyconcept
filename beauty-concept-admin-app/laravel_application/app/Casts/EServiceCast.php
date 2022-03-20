<?php
/*
 * File name: EServiceCast.php
 * Last modified: 2022.02.15 at 13:41:44
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Casts;

use App\Models\EService;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

/**
 * Class EServiceCast
 * @package App\Casts
 */
class EServiceCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): EService
    {
        $decodedValue = json_decode($value, true);
        $eService = EService::find($decodedValue['id']);
        // service exist in database
        if (!empty($eService)) {
            return $eService;
        }
        // if not exist the clone will load
        // create new service based on values stored on database
        $eService = new EService($decodedValue);
        // push id attribute fillable array
        array_push($eService->fillable, 'id');
        // assign the id to service object
        $eService->id = $decodedValue['id'];
        return $eService;
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
//        if (!$value instanceof EService) {
//            throw new InvalidArgumentException('The given value is not an EService instance.');
//        }

        return [
            'e_service' => json_encode(
                [
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'price' => $value['price'],
                    'discount_price' => $value['discount_price'],
                    'duration' => $value['duration'],
                    'enable_booking' => $value['enable_booking'],
                ]
            )
        ];
    }
}
