<?php
/*
 * File name: SalonPayoutRepository.php
 * Last modified: 2022.02.02 at 21:22:02
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Repositories;

use App\Models\SalonPayout;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SalonPayoutRepository
 * @package App\Repositories
 * @version January 30, 2021, 11:17 am UTC
 *
 * @method SalonPayout findWithoutFail($id, $columns = ['*'])
 * @method SalonPayout find($id, $columns = ['*'])
 * @method SalonPayout first($columns = ['*'])
 */
class SalonPayoutRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'salon_id',
        'method',
        'amount',
        'paid_date',
        'note'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SalonPayout::class;
    }
}
