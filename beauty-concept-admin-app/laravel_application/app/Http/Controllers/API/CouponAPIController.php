<?php
/*
 * File name: CouponAPIController.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Controllers\API;


use App\Criteria\Coupons\ValidCriteria;
use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Repositories\CouponRepository;
use App\Repositories\EServiceRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CouponController
 * @package App\Http\Controllers\API
 */
class CouponAPIController extends Controller
{
    /** @var  CouponRepository */
    private $couponRepository;
    /** @var EServiceRepository */
    private $eServiceRepository;

    public function __construct(CouponRepository $couponRepo, EServiceRepository $eServiceRepository)
    {
        $this->couponRepository = $couponRepo;
        $this->eServiceRepository = $eServiceRepository;
    }

    /**
     * Display a listing of the Coupon.
     * GET|HEAD /coupons
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'code' => 'required',
                'e_services_id' => 'required',
            ]);
            $this->couponRepository->pushCriteria(new ValidCriteria($request));
            $eServices = $this->eServiceRepository->findWhereIn('id', explode(',', $request->get('e_services_id')));
            $coupon = $this->couponRepository->first();
            if (!empty($coupon)) {
                $coupon = $coupon->getValue($eServices);
            }
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($coupon, 'Coupons retrieved successfully');
    }

    /**
     * Display the specified Coupon.
     * GET|HEAD /coupons/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        /** @var Coupon $coupon */
        if (!empty($this->couponRepository)) {
            $coupon = $this->couponRepository->findWithoutFail($id);
        }

        if (empty($coupon)) {
            return $this->sendError('Coupon not found');
        }

        return $this->sendResponse($coupon->toArray(), 'Coupon retrieved successfully');
    }
}
