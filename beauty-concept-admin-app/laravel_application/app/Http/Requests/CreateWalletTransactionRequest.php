<?php
/*
 * File name: CreateWalletTransactionRequest.php
 * Last modified: 2021.08.10 at 18:04:14
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Requests;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Foundation\Http\FormRequest;

class CreateWalletTransactionRequest extends FormRequest
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
        if ($this->get('action') == 'debit') {
            $wallet = Wallet::find($this->get('wallet_id'));
            $max = isset($wallet) ? $wallet->balance : 0;
            WalletTransaction::$rules['amount'] = "required|numeric|min:0.01|max:$max";
        }
        return WalletTransaction::$rules;
    }
}
