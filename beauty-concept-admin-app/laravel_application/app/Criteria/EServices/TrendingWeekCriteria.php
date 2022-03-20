<?php
/*
 * File name: TrendingWeekCriteria.php
 * Last modified: 2022.02.02 at 21:31:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Criteria\EServices;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class TrendingWeekCriteria.
 *
 * @package namespace App\Criteria\EServices;
 */
class TrendingWeekCriteria implements CriteriaInterface
{
    // TODO TrendingWeekCriteria
    /**
     * @var array
     */
    private $request;

    /**
     * TrendingWeekCriteria constructor.
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

            $myLat = $this->request->get('myLat', 0);
            $myLon = $this->request->get('myLon', 0);
            $areaLat = $this->request->get('areaLat', 0);
            $areaLon = $this->request->get('areaLon', 0);

            return $model->join('salons', 'salons.id', '=', 'e_services.salon_id')->select(DB::raw("SQRT(
            POW(69.1 * (salons.latitude - $myLat), 2) +
            POW(69.1 * ($myLon - salons.longitude) * COS(salons.latitude / 57.3), 2)) AS distance, SQRT(
            POW(69.1 * (salons.latitude - $areaLat), 2) +
            POW(69.1 * ($areaLon - salons.longitude) * COS(salons.latitude / 57.3), 2)) AS area, count(e_services.id) as e_service_count"), 'e_services.*')
                ->join('e_service_bookings', 'e_services.id', '=', 'e_service_bookings.e_service_id')
                ->whereBetween('e_service_bookings.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('salons.active', '1')
                ->orderBy('e_service_count', 'desc')
                ->orderBy('area')
                ->groupBy('e_services.id');
        } else {
            return $model->join('e_service_bookings', 'e_services.id', '=', 'e_service_bookings.e_service_id')
                ->join('salons', 'salons.id', '=', 'e_services.salon_id')
                ->whereBetween('e_service_bookings.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->where('salons.active', '1')
                ->groupBy('e_services.id')
                ->orderBy('e_service_count', 'desc')
                ->select('e_services.*', DB::raw('count(e_services.id) as e_service_count'));
        }
    }
}
