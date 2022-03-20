<?php
/*
 * File name: SalonCast.php
 * Last modified: 2022.02.15 at 13:33:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Casts;

use App\Models\Salon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

/**
 * Class SalonCast
 * @package App\Casts
 */
class SalonCast implements CastsAttributes
{

    /**
     * @inheritDoc
     */
    public function get($model, string $key, $value, array $attributes): Salon
    {
        $decodedValue = json_decode($value, true);
        $salon = Salon::find($decodedValue['id']);
        // salon exist in database
        if (!empty($salon)) {
            return $salon;
        }
        // if not exist the clone will loaded
        // create new salon based on values stored on database
        $salon = new Salon($decodedValue);
        // push id attribute fillable array
        array_push($salon->fillable, 'id');
        // assign the id to salon object
        $salon->id = $decodedValue['id'];
        return $salon;
    }

    /**
     * @inheritDoc
     */
    public function set($model, string $key, $value, array $attributes): array
    {
//        if (!$value instanceof \Eloquent) {
//            throw new InvalidArgumentException('The given value is not an Salon instance.');
//        }
        return [
            'salon' => json_encode([
                'id' => $value['id'],
                'name' => $value['name'],
                'phone_number' => $value['phone_number'],
                'mobile_number' => $value['mobile_number'],
            ])
        ];
    }
}
