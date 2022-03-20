<?php
/*
 * File name: WalletRepository.php
 * Last modified: 2021.08.10 at 18:04:14
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Repositories;

use App\Models\Wallet;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class WalletRepository
 * @package App\Repositories
 * @version August 8, 2021, 1:41 pm CEST
 *
 * @method Wallet findWithoutFail($id, $columns = ['*'])
 * @method Wallet find($id, $columns = ['*'])
 * @method Wallet first($columns = ['*'])
 */
class WalletRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'balance',
        'currency',
        'user_id',
        'enabled'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Wallet::class;
    }
}
