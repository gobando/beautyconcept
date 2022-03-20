<?php
/*
 * File name: SalonLevelAPIController.php
 * Last modified: 2022.02.03 at 10:46:03
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;
use App\Models\SalonLevel;
use App\Repositories\SalonLevelRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class SalonLevelController
 * @package App\Http\Controllers\API
 */
class SalonLevelAPIController extends Controller
{
    /** @var  SalonLevelRepository */
    private $salonLevelRepository;

    public function __construct(SalonLevelRepository $salonLevelRepo)
    {
        $this->salonLevelRepository = $salonLevelRepo;
    }

    /**
     * Display a listing of the SalonLevel.
     * GET|HEAD /salonLevels
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $this->salonLevelRepository->pushCriteria(new RequestCriteria($request));
            $this->salonLevelRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $salonLevels = $this->salonLevelRepository->all();

        return $this->sendResponse($salonLevels->toArray(), 'E Provider Types retrieved successfully');
    }

    /**
     * Display the specified SalonLevel.
     * GET|HEAD /salonLevels/{id}
     *
     * @param int $id
     *
     * @return JsonResponse
     */
    public function show($id)
    {
        /** @var SalonLevel $salonLevel */
        if (!empty($this->salonLevelRepository)) {
            $salonLevel = $this->salonLevelRepository->findWithoutFail($id);
        }

        if (empty($salonLevel)) {
            return $this->sendError('E Provider Type not found');
        }

        return $this->sendResponse($salonLevel->toArray(), 'E Provider Type retrieved successfully');
    }
}
