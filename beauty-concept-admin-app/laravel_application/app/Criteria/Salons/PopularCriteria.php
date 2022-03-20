<?php
/*
 * File name: PopularCriteria.php
 * Last modified: 2022.02.02 at 21:31:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Criteria\Salons;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class PopularCriteria.
 *
 * @package namespace App\Criteria\Salons;
 */
class PopularCriteria implements CriteriaInterface
{
    // TODO Popular eService
    /**
     * @var array
     */
    private $request;

    /**
     * PopularCriteria constructor.
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
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
        if ($this->request->has(['myLon', 'myLat', 'areaLon', 'areaLat'])) {

            $myLat = $this->request->get('myLat');
            $myLon = $this->request->get('myLon');
            $areaLat = $this->request->get('areaLat');
            $areaLon = $this->request->get('areaLon');

            return $model->select(DB::raw("SQRT(
                POW(69.1 * (latitude - $myLat), 2) +
                POW(69.1 * ($myLon - longitude) * COS(latitude / 57.3), 2)) AS distance, SQRT(
                POW(69.1 * (latitude - $areaLat), 2) +
                POW(69.1 * ($areaLon - longitude) * COS(latitude / 57.3), 2))  AS area count(salons.id) as salon_count"), "salons.*")
                ->join('e_services', 'e_services.salon_id', '=', 'salons.id')
                ->join('e_service_bookings', 'e_services.id', '=', 'e_service_bookings.e_service_id')
                ->orderBy('salon_count', 'desc')
                ->orderBy('area')
                ->groupBy('salons.id');
        } else {
            return $model->select(DB::raw("count(salons.id) as salon_count"), "salons.*")
                ->join('e_services', 'e_services.salon_id', '=', 'salons.id')
                ->join('e_service_bookings', 'e_services.id', '=', 'e_service_bookings.e_service_id')
                ->orderBy('salon_count', 'desc')
                ->groupBy('salons.id');
        }
    }
}
