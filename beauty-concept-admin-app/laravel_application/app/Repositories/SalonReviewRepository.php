<?php
/*
 * File name: SalonReviewRepository.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Repositories;

use App\Models\SalonReview;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SalonReviewRepository
 * @package App\Repositories
 * @version January 23, 2021, 7:42 pm UTC
 *
 * @method SalonReview findWithoutFail($id, $columns = ['*'])
 * @method SalonReview find($id, $columns = ['*'])
 * @method SalonReview first($columns = ['*'])
 */
class SalonReviewRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'review',
        'rate',
        'booking_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SalonReview::class;
    }
}
