<?php
/*
 * File name: WalletTransactionAPIController.php
 * Last modified: 2021.08.11 at 01:13:13
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;


use App\Criteria\Wallets\WalletTransactionsOfUserCriteria;
use App\Http\Controllers\Controller;
use App\Repositories\WalletTransactionRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;

/**
 * Class WalletTransactionController
 * @package App\Http\Controllers\API
 */
class WalletTransactionAPIController extends Controller
{
    /** @var  WalletTransactionRepository */
    private $walletTransactionRepository;

    public function __construct(WalletTransactionRepository $walletTransactionRepo)
    {
        $this->walletTransactionRepository = $walletTransactionRepo;
    }

    /**
     * Display a listing of the WalletTransaction.
     * GET|HEAD /walletTransactions
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->walletTransactionRepository->pushCriteria(new RequestCriteria($request));
            $this->walletTransactionRepository->pushCriteria(new WalletTransactionsOfUserCriteria(auth()->id()));
            $this->walletTransactionRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage());
        }
        $walletTransactions = $this->walletTransactionRepository->orderBy('wallet_transactions.created_at', 'desc')->all();

        return $this->sendResponse($walletTransactions->toArray(), 'Wallet Transactions retrieved successfully');
    }
}
