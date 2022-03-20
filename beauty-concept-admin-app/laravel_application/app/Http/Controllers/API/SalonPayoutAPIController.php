<?php
/*
 * File name: SalonPayoutAPIController.php
 * Last modified: 2022.02.02 at 21:21:33
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\SalonPayout;
use App\Repositories\SalonPayoutRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class SalonPayoutController
 * @package App\Http\Controllers\API
 */
class SalonPayoutAPIController extends Controller
{
    /** @var  SalonPayoutRepository */
    private $salonPayoutRepository;

    public function __construct(SalonPayoutRepository $salonPayoutRepo)
    {
        $this->salonPayoutRepository = $salonPayoutRepo;
    }

    /**
     * Display a listing of the SalonPayout.
     * GET|HEAD /salonPayouts
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->salonPayoutRepository->pushCriteria(new RequestCriteria($request));
            $this->salonPayoutRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $salonPayouts = $this->salonPayoutRepository->all();

        return $this->sendResponse($salonPayouts->toArray(), 'E Provider Payouts retrieved successfully');
    }

    /**
     * Display the specified SalonPayout.
     * GET|HEAD /salonPayouts/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        /** @var SalonPayout $salonPayout */
        if (!empty($this->salonPayoutRepository)) {
            $salonPayout = $this->salonPayoutRepository->findWithoutFail($id);
        }

        if (empty($salonPayout)) {
            return $this->sendError('E Provider Payout not found');
        }

        return $this->sendResponse($salonPayout->toArray(), 'E Provider Payout retrieved successfully');
    }
}
