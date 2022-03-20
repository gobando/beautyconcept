<?php
/*
 * File name: UpdateBookingEarningTable.php
 * Last modified: 2022.02.16 at 17:42:22
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Listeners;

use App\Criteria\Bookings\BookingsOfSalonCriteria;
use App\Criteria\Bookings\PaidBookingsCriteria;
use App\Repositories\BookingRepository;
use App\Repositories\EarningRepository;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class UpdateBookingEarningTable
 * @package App\Listeners
 */
class UpdateBookingEarningTable
{
    /**
     * @var EarningRepository
     */
    private $earningRepository;

    /**
     * @var BookingRepository
     */
    private $bookingRepository;

    /**
     * Create the event listener.
     *
     * @param EarningRepository $earningRepository
     * @param BookingRepository $bookingRepository
     */
    public function __construct(EarningRepository $earningRepository, BookingRepository $bookingRepository)
    {
        $this->earningRepository = $earningRepository;
        $this->bookingRepository = $bookingRepository;
    }

    /**
     * Handle the event.
     * oldBooking
     * updatedBooking
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        try {
            $salon = $event->booking->salon;
            $this->bookingRepository->pushCriteria(new BookingsOfSalonCriteria($salon->id));
            $this->bookingRepository->pushCriteria(new PaidBookingsCriteria());
            $bookings = $this->bookingRepository->all();
            $bookingsCount = $bookings->count();

            $bookingsTotals = $bookings->map(function ($booking) {
                return $booking->getTotal();
            })->toArray();

            $bookingsTaxes = $bookings->map(function ($booking) {
                return $booking->getTaxesValue();
            })->toArray();

            $total = array_reduce($bookingsTotals, function ($total1, $total2) {
                return $total1 + $total2;
            }, 0);

            $tax = array_reduce($bookingsTaxes, function ($tax1, $tax2) {
                return $tax1 + $tax2;
            }, 0);
            $this->earningRepository->updateOrCreate(['salon_id' => $salon->id], [
                    'total_bookings' => $bookingsCount,
                    'total_earning' => $total - $tax,
                    'taxes' => $tax,
                    'admin_earning' => ($total - $tax) * (100 - $salon->salonLevel->commission) / 100,
                    'salon_earning' => ($total - $tax) * $salon->salonLevel->commission / 100,
                ]
            );
        } catch (ValidatorException | RepositoryException $e) {
        } finally {
            $this->bookingRepository->resetCriteria();
        }
    }
}
