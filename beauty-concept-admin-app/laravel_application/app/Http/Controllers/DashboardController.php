<?php
/*
 * File name: DashboardController.php
 * Last modified: 2022.02.02 at 21:31:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Controllers;

use App\Repositories\BookingRepository;
use App\Repositories\EarningRepository;
use App\Repositories\SalonRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Response;
use Illuminate\View\View;

class DashboardController extends Controller
{

    /** @var  BookingRepository */
    private $bookingRepository;


    /**
     * @var UserRepository
     */
    private $userRepository;

    /** @var  SalonRepository */
    private $salonRepository;
    /** @var  EarningRepository */
    private $earningRepository;

    public function __construct(BookingRepository $bookingRepo, UserRepository $userRepo, EarningRepository $earningRepository, SalonRepository $salonRepo)
    {
        parent::__construct();
        $this->bookingRepository = $bookingRepo;
        $this->userRepository = $userRepo;
        $this->salonRepository = $salonRepo;
        $this->earningRepository = $earningRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|Response|View
     */
    public function index()
    {
        $bookingsCount = $this->bookingRepository->count();
        $membersCount = $this->userRepository->count();
        $salonsCount = $this->salonRepository->count();
        $salons = $this->salonRepository->orderBy('id', 'desc')->limit(4);
        $earning = $this->earningRepository->all()->sum('total_earning');
        $ajaxEarningUrl = route('payments.byMonth', ['api_token' => auth()->user()->api_token]);
        return view('dashboard.index')
            ->with("ajaxEarningUrl", $ajaxEarningUrl)
            ->with("bookingsCount", $bookingsCount)
            ->with("salonsCount", $salonsCount)
            ->with("salons", $salons)
            ->with("membersCount", $membersCount)
            ->with("earning", $earning);
    }
}
