<?php
/*
 * File name: AwardRepository.php
 * Last modified: 2022.02.02 at 21:22:03
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Repositories;

use App\Models\Award;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AwardRepository
 * @package App\Repositories
 * @version January 12, 2021, 10:59 am UTC
 *
 * @method Award findWithoutFail($id, $columns = ['*'])
 * @method Award find($id, $columns = ['*'])
 * @method Award first($columns = ['*'])
 */
class AwardRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'title',
        'description',
        'salon_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Award::class;
    }
}
