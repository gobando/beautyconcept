<?php
/*
 * File name: BookingsOfSalonCriteria.php
 * Last modified: 2022.02.02 at 21:26:20
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Criteria\Bookings;

use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class BookingsOfSalonCriteria.
 *
 * @package namespace App\Criteria\Bookings;
 */
class BookingsOfSalonCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    private $salonId;

    /**
     * BookingsOfSalonCriteria constructor.
     */
    public function __construct($salonId)
    {
        $this->salonId = $salonId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $salonId = DB::raw("json_extract(salon, '$.id')");
        return $model->where($salonId, $this->salonId)
            ->where('payment_status_id', '2')
            ->groupBy('bookings.id')
            ->select('bookings.*');

    }
}
