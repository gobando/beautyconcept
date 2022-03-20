<?php
/*
 * File name: StripeFPXController.php
 * Last modified: 2021.07.24 at 16:24:06
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers;

use Flash;
use Illuminate\Http\Request;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeFPXController extends ParentBookingController
{

    public function __init()
    {
        Stripe::setApiKey(setting('stripe_fpx_secret'));
        Stripe::setClientId(setting('stripe_fpx_key'));
    }

    public function index()
    {
        return view('home');
    }

    public function checkout(Request $request)
    {
        $this->booking = $this->bookingRepository->findWithoutFail($request->get('booking_id'));

        if (empty($this->booking)) {
            Flash::error("Error processing Stripe FPX payment for your booking");
            return redirect(route('payments.failed'));
        }
        try {
            $stripeCart = $this->getBookingData();
            $intent = PaymentIntent::create($stripeCart);
        } catch (ApiErrorException $e) {
            Flash::error($e->getMessage());
            return redirect(route('payments.failed'));
        }
        return view('payment_methods.stripe_fpx_charge', ['booking' => $this->booking, 'intent' => $intent]);
    }

    /**
     * Set cart data for processing payment on Stripe.
     */
    private function getBookingData(): array
    {
        $data = [];
        $amount = $this->booking->getTotal();
        $data['amount'] = (int)($amount * 100);
        $data['payment_method_types'] = ['fpx'];
        $data['currency'] = "myr"; //setting('default_currency_code');
        return $data;
    }

    public function paySuccess(Request $request, int $bookingId)
    {
        $this->booking = $this->bookingRepository->findWithoutFail($bookingId);

        if (empty($this->booking)) {
            Flash::error("Error processing Stripe payment for your booking");
            return redirect(route('payments.failed'));
        } else {
            try {
                $intent = PaymentIntent::retrieve($request->get('payment_intent'));
                if ($intent->status == 'succeeded') {
                    $this->paymentMethodId = 10; // Stripe FPX method id
                    $this->createBooking();
                    return redirect()->to("payments/stripe-fpx");
                } else {
                    return $this->sendError("Error processing Stripe payment for your booking");
                }
            } catch (ApiErrorException $e) {
                Flash::error($e->getMessage());
                return redirect(route('payments.failed'));
            }
        }
    }
}
