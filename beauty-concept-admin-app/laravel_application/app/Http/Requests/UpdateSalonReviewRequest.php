<?php
/*
 * File name: UpdateSalonReviewRequest.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Requests;

use App\Models\SalonReview;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSalonReviewRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        SalonReview::$rules['booking_id'] = 'not_regex:/.+/';
        return SalonReview::$rules;
    }
}
