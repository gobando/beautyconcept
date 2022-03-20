<?php
/*
 * File name: CouponsOfUserCriteria.php
 * Last modified: 2022.02.03 at 18:14:47
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Criteria\Coupons;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class CouponsOfUserCriteria.
 *
 * @package namespace App\Criteria\Coupons;
 */
class CouponsOfUserCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    private $userId;

    /**
     * CouponsOfUserCriteria constructor.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if (auth()->user()->hasRole('admin')) {
            return $model;
        } elseif (auth()->user()->hasRole('salon owner')) {
            $salons = $model->join("discountables", "discountables.coupon_id", "=", "coupons.id")
                ->join("salon_users", "salon_users.salon_id", "=", "discountables.discountable_id")
                ->where('discountable_type', 'App\\Models\\Salon')
                ->where("salon_users.user_id", $this->userId)
                ->select("coupons.*");

            return $model->join("discountables", "discountables.coupon_id", "=", "coupons.id")
                ->join("e_services", "e_services.id", "=", "discountables.discountable_id")
                ->where('discountable_type', 'App\\Models\\EService')
                ->join("salon_users", "salon_users.salon_id", "=", "e_services.salon_id")
                ->where("salon_users.user_id", $this->userId)
                ->select("coupons.*")
                ->union($salons);
        } else {
            return $model;
        }

    }
}
