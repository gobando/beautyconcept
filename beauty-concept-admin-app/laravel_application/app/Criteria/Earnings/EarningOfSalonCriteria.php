<?php
/*
 * File name: EarningOfSalonCriteria.php
 * Last modified: 2022.02.02 at 21:31:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Criteria\Earnings;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class EarningOfSalonCriteriaCriteria.
 *
 * @package namespace App\Criteria\Earnings;
 */
class EarningOfSalonCriteria implements CriteriaInterface
{
    private $salonId;

    /**
     * EarningOfSalonCriteriaCriteria constructor.
     */
    public function __construct($salonId)
    {
        $this->salonId = $salonId;
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
        return $model->where("salon_id", $this->salonId);
    }
}
