<?php
/*
 * File name: WalletAPIController.php
 * Last modified: 2021.08.11 at 01:13:13
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;


use App\Criteria\Wallets\CurrentCurrencyWalletsCriteria;
use App\Criteria\Wallets\EnabledCriteria;
use App\Criteria\Wallets\WalletsOfUserCriteria;
use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Repositories\CurrencyRepository;
use App\Repositories\WalletRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class WalletController
 * @package App\Http\Controllers\API
 */
class WalletAPIController extends Controller
{
    /** @var  WalletRepository */
    private $walletRepository;

    /**  @var  CurrencyRepository */
    private $currencyRepository;

    public function __construct(WalletRepository $walletRepo, CurrencyRepository $currencyRepository)
    {
        $this->walletRepository = $walletRepo;
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * Display a listing of the Wallet.
     * GET|HEAD /wallets
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->walletRepository->pushCriteria(new RequestCriteria($request));
            $this->walletRepository->pushCriteria(new EnabledCriteria());
            $this->walletRepository->pushCriteria(new WalletsOfUserCriteria(auth()->id()));
            $this->walletRepository->pushCriteria(new CurrentCurrencyWalletsCriteria());
            $this->walletRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $wallets = $this->walletRepository->orderBy('wallets.balance', 'desc')->all();

        return $this->sendResponse($wallets->toArray(), 'Wallets retrieved successfully');
    }

    /**
     * Store a newly created Wallet in storage.
     * POST /notifications
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'name' => Wallet::$rules['name'],
            ]);
            $currency = $this->currencyRepository->findWithoutFail(setting('default_currency_id'));
            if (empty($currency)) {
                return $this->sendError('Default Currency not found', 200);
            }
            $input = [];
            $input['name'] = $request->get('name');
            $input['currency'] = $currency;
            $input['user_id'] = auth()->id();
            $input['balance'] = 0;
            $input['enabled'] = 1;
            $wallet = $this->walletRepository->create($input);
        } catch (Exception | ValidationException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($wallet->toArray(), __('lang.saved_successfully', ['operator' => __('lang.wallet')]));
    }

    /**
     * Update the specified Notification in storage.
     *
     * @param $id
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function update($id, Request $request): JsonResponse
    {
        try {
            $this->walletRepository->pushCriteria(new EnabledCriteria());
            $this->walletRepository->pushCriteria(new WalletsOfUserCriteria(auth()->id()));
            $wallet = $this->walletRepository->findWithoutFail($id);
            if (empty($wallet)) {
                return $this->sendError('Wallet not found', 200);
            }
            $this->validate($request, [
                'name' => Wallet::$rules['name'],
            ]);
            $input = [];
            $input['name'] = $request->get('name');
            $wallet = $this->walletRepository->update($input, $id);
        } catch (ValidatorException | ValidationException | RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse($wallet->toArray(), __('lang.saved_successfully', ['operator' => __('lang.wallet')]));
    }

    /**
     * Remove the specified Favorite from storage.
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->walletRepository->pushCriteria(new EnabledCriteria());
            $this->walletRepository->pushCriteria(new WalletsOfUserCriteria(auth()->id()));
            $wallet = $this->walletRepository->findWithoutFail($id);
            if (empty($wallet)) {
                return $this->sendError('Wallet not found');
            }
            if ($this->walletRepository->delete($id) < 1) {
                return $this->sendError('Wallet not deleted');
            }
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }

        return $this->sendResponse(true, __('lang.deleted_successfully', ['operator' => __('lang.wallet')]));

    }
}
