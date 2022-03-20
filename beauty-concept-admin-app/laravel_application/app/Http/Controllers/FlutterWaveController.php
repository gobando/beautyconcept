<?php
/*
 * File name: FlutterWaveController.php
 * Last modified: 2021.07.23 at 15:29:47
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers;

use Flash;
use Illuminate\Http\Request;

class FlutterWaveController extends ParentBookingController
{

    public function __init()
    {

    }

    public function index()
    {
        return view('home');
    }

    public function checkout(Request $request)
    {
        $this->booking = $this->bookingRepository->findWithoutFail($request->get('booking_id'));
        if (empty($this->booking)) {
            Flash::error("Error processing FlutterWave payment for your booking");
            return redirect(route('payments.failed'));
        }
        return view('payment_methods.flutterwave_charge', ['booking' => $this->booking]);
    }

    public function paySuccess(Request $request, int $bookingId, string $transactionId)
    {
        $this->booking = $this->bookingRepository->findWithoutFail($bookingId);

        if (empty($this->booking)) {
            Flash::error("Error processing FlutterWave payment for your booking");
            return redirect(route('payments.failed'));
        } else {

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transactionId/verify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer " . setting('flutterwave_secret'),
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return $this->sendError($err);
            } else {
                $this->paymentMethodId = 9; // FlutterWave method id
                $this->createBooking();
                return $this->sendResponse($response, __('lang.saved_successfully'));
            }
        }
    }
}
