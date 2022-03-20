<?php
/*
 * File name: EServicesOfSalonCriteria.php
 * Last modified: 2022.02.02 at 21:31:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Criteria\EServices;


use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class EServicesOfSalonCriteria.
 *
 * @package namespace App\Criteria\EServices;
 */
class EServicesOfSalonCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    private $salonId;

    /**
     * EServicesOfSalonCriteria constructor.
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
        return $model->where('salon_id', '=', $this->salonId);
    }
}
