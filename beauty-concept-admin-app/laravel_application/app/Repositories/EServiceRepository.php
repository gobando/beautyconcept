<?php
/*
 * File name: EServiceRepository.php
 * Last modified: 2022.02.02 at 21:52:24
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Repositories;

use App\Models\EService;
use InfyOm\Generator\Common\BaseRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class EServiceRepository
 * @package App\Repositories
 * @version January 19, 2021, 1:59 pm UTC
 *
 * @method EService findWithoutFail($id, $columns = ['*'])
 * @method EService find($id, $columns = ['*'])
 * @method EService first($columns = ['*'])
 */
class EServiceRepository extends BaseRepository implements CacheableInterface
{

    use CacheableRepository;

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'price',
        'discount_price',
        'duration',
        'description',
        'featured',
        'available',
        'salon_id'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return EService::class;
    }

    /**
     * @return array
     */
    public function groupedBySalons(): array
    {
        $eServices = [];
        foreach ($this->all() as $model) {
            if (!empty($model->salon)) {
                $eServices[$model->salon->name][$model->id] = $model->name;
            }
        }
        return $eServices;
    }
}
