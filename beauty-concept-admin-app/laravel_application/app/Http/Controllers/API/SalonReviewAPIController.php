<?php
/*
 * File name: SalonReviewAPIController.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Controllers\API;


use App\Criteria\Bookings\BookingsOfUserCriteria;
use App\Criteria\SalonReviews\SalonReviewsOfUserCriteria;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateSalonReviewRequest;
use App\Repositories\BookingRepository;
use App\Repositories\SalonReviewRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class SalonReviewController
 * @package App\Http\Controllers\API
 */
class SalonReviewAPIController extends Controller
{
    /** @var  SalonReviewRepository */
    private $salonReviewRepository;

    /** @var  BookingRepository */
    private $bookingRepository;

    public function __construct(SalonReviewRepository $salonReviewRepo, BookingRepository $bookingRepository)
    {
        $this->salonReviewRepository = $salonReviewRepo;
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Display a listing of the SalonReview.
     * GET|HEAD /salonReviews
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->salonReviewRepository->pushCriteria(new RequestCriteria($request));
            if (auth()->check()) {
                $this->salonReviewRepository->pushCriteria(new SalonReviewsOfUserCriteria(auth()->id()));
            }
            $this->salonReviewRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $salonReviews = $this->salonReviewRepository->all();
        $this->filterCollection($request, $salonReviews);

        return $this->sendResponse($salonReviews->toArray(), 'E Service Reviews retrieved successfully');
    }

    /**
     * Display the specified SalonReview.
     * GET|HEAD /salonReviews/{id}
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function show(int $id, Request $request): JsonResponse
    {
        try {
            $this->salonReviewRepository->pushCriteria(new RequestCriteria($request));
            $this->salonReviewRepository->pushCriteria(new LimitOffsetCriteria($request));

        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $salonReview = $this->salonReviewRepository->findWithoutFail($id);
        if (empty($salonReview)) {
            return $this->sendError(__('lang.not_found', ['operator' => __('lang.salon_review')]));
        }
        $this->filterModel($request, $salonReview);

        return $this->sendResponse($salonReview->toArray(), 'E Service Review retrieved successfully');
    }

    /**
     * Store a newly created Review in storage.
     *
     * @param CreateSalonReviewRequest $request
     *
     * @return JsonResponse
     */
    public function store(CreateSalonReviewRequest $request): JsonResponse
    {
        $bookingId = $request->only("booking_id");
        $input = $request->only('rate', 'review');

        try {
            $this->bookingRepository->pushCriteria(new BookingsOfUserCriteria(auth()->id()));
            $booking = $this->bookingRepository->findWithoutFail($bookingId);
            if (empty($booking)) {
                $this->sendError(__('lang.not_found', ['operator' => __('lang.booking')]));
            }
            $review = $this->salonReviewRepository->updateOrCreate($bookingId, $input);
        } catch (RepositoryException | ValidatorException $e) {
            return $this->sendError(__('lang.not_found', ['operator' => __('lang.salon_review')]));
        }

        return $this->sendResponse($review->toArray(), __('lang.saved_successfully', ['operator' => __('lang.salon_review')]));
    }
}
