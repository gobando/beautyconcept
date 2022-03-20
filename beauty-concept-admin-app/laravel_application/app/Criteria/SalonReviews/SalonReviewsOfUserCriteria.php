<?php
/*
 * File name: SalonReviewsOfUserCriteria.php
 * Last modified: 2022.02.15 at 16:30:52
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Criteria\SalonReviews;

use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class SalonReviewsOfUserCriteria.
 *
 * @package namespace App\Criteria\SalonReviews;
 */
class SalonReviewsOfUserCriteria implements CriteriaInterface
{
    /**
     * @var int
     */
    private $userId;

    /**
     * SalonReviewsOfUserCriteria constructor.
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
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
        if (auth()->check() && auth()->user()->hasRole('admin')) {
            return $model->select('salon_reviews.*');
        } else if (auth()->check() && auth()->user()->hasRole('salon owner')) {
            return $model->join("bookings", "bookings.id", "=", "salon_reviews.booking_id")
                ->join("salon_users", "salon_users.salon_id", "=", "bookings.salon->id")
                ->where('salon_users.user_id', $this->userId)
                ->groupBy('salon_reviews.id')
                ->select('salon_reviews.*');
        } else if (auth()->check() && auth()->user()->hasRole('customer')) {
            return $model->join("bookings", "bookings.id", "=", "salon_reviews.booking_id")
                ->where('bookings.user_id', $this->userId)
                ->select('salon_reviews.*');
        } else {
            return $model->select('salon_reviews.*');
        }
    }
}
