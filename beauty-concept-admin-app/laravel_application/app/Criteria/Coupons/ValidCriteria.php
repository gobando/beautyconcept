<?php
/*
 * File name: ValidCriteria.php
 * Last modified: 2022.02.12 at 22:29:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Criteria\Coupons;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ValidCriteriaCriteria.
 *
 * @package namespace App\Criteria\Coupons;
 */
class ValidCriteria implements CriteriaInterface
{
    /**
     * @var array
     */
    private $request;

    /**
     * ValidCriteria constructor.
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
        return $model->join("discountables", "discountables.coupon_id", "=", "coupons.id")
            ->where(function ($query) {
                if ($this->request->has('e_service_id')) {
                    $query->orWhere(function ($query) {
                        $query->where('discountable_type', 'App\\Models\\EService')
                            ->whereIn('discountable_id', explode(',', $this->request->get('e_services_id')));
                    });
                }
                if ($this->request->has('salon_id')) {
                    $query->orWhere(function ($query) {
                        $query->where('discountable_type', 'App\\Models\\Salon')
                            ->where('discountable_id', $this->request->get('salon_id'));
                    });
                }
                if ($this->request->has('categories_id')) {
                    $query->orWhere(function ($query) {
                        $query->where('discountable_type', 'App\\Models\\Category')
                            ->where('discountable_id', explode(',', $this->request->get('categories_id')));
                    });
                }
            })
            ->where('code', $this->request->get('code'))
            ->where('enabled', '1')->where('expires_at', '>', Carbon::now())->select('coupons.*');
    }
}
