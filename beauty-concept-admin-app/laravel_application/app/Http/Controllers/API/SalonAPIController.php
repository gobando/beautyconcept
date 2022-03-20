<?php
/*
 * File name: SalonAPIController.php
 * Last modified: 2022.02.04 at 17:24:24
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Controllers\API;


use App\Criteria\Salons\NearCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\SalonRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class SalonController
 * @package App\Http\Controllers\API
 */
class SalonAPIController extends Controller
{
    /** @var  SalonRepository */
    private $salonRepository;

    public function __construct(SalonRepository $salonRepo)
    {
        $this->salonRepository = $salonRepo;
    }

    /**
     * Display a listing of the Salon.
     * GET|HEAD /salons
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->salonRepository->pushCriteria(new RequestCriteria($request));
            $this->salonRepository->pushCriteria(new LimitOffsetCriteria($request));
            $this->salonRepository->pushCriteria(new NearCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $salons = $this->salonRepository->all();
        $this->filterCollection($request, $salons);

        return $this->sendResponse($salons->toArray(), 'Salons retrieved successfully');
    }

    /**
     * Display the specified Salon.
     * GET|HEAD /salons/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $this->salonRepository->pushCriteria(new RequestCriteria($request));
            $this->salonRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $salon = $this->salonRepository->findWithoutFail($id);
        if (empty($salon)) {
            return $this->sendError('Salon not found');
        }
        $this->filterModel($request, $salon);
        $array = $this->orderAvailabilityHours($salon);
        return $this->sendResponse($array, 'Salon retrieved successfully');
    }

    private function orderAvailabilityHours($salon)
    {
        $array = $salon->toArray();
        if (isset($array['availability_hours'])) {
            $availabilityHours = $array['availability_hours'];
            $availabilityHours = collect($availabilityHours);
            $availabilityHours = $availabilityHours->sortBy(function ($item, $key) {
                return Carbon::createFromIsoFormat('dddd', $item['day'])->dayOfWeek;
            });
            $array['availability_hours'] = array_values($availabilityHours->toArray());
        }
        return $array;
    }
}
