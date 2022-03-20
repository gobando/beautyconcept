<?php
/*
 * File name: CreateSalonLevelRequest.php
 * Last modified: 2022.02.02 at 21:19:16
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Requests;

use App\Models\SalonLevel;
use Illuminate\Foundation\Http\FormRequest;

class CreateSalonLevelRequest extends FormRequest
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
    public function rules()
    {
        return SalonLevel::$rules;
    }
}
