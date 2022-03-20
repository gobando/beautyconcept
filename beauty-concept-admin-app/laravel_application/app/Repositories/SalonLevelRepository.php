<?php
/*
 * File name: SalonLevelRepository.php
 * Last modified: 2022.02.03 at 14:23:26
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Repositories;

use App\Models\SalonLevel;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class SalonLevelRepository
 * @package App\Repositories
 * @version January 13, 2021, 10:56 am UTC
 *
 * @method SalonLevel findWithoutFail($id, $columns = ['*'])
 * @method SalonLevel find($id, $columns = ['*'])
 * @method SalonLevel first($columns = ['*'])
 */
class SalonLevelRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'commission',
        'disabled',
        'default'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return SalonLevel::class;
    }
}
