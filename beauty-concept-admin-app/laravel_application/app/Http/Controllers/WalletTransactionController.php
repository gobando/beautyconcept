<?php
/*
 * File name: WalletTransactionController.php
 * Last modified: 2021.08.10 at 18:03:35
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers;

use App\Criteria\Wallets\EnabledCriteria;
use App\DataTables\WalletTransactionDataTable;
use App\Http\Requests\CreateWalletTransactionRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Repositories\WalletTransactionRepository;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

class WalletTransactionController extends Controller
{
    /** @var  WalletTransactionRepository */
    private $walletTransactionRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var WalletRepository
     */
    private $walletRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(WalletTransactionRepository $walletTransactionRepo, CustomFieldRepository $customFieldRepo, WalletRepository $walletRepo, UserRepository $userRepo)
    {
        parent::__construct();
        $this->walletTransactionRepository = $walletTransactionRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->walletRepository = $walletRepo;
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the WalletTransaction.
     *
     * @param WalletTransactionDataTable $walletTransactionDataTable
     * @return Response
     */
    public function index(WalletTransactionDataTable $walletTransactionDataTable)
    {
        return $walletTransactionDataTable->render('wallet_transactions.index');
    }

    /**
     * Show the form for creating a new WalletTransaction.
     *
     * @return Application|Factory|View
     * @throws RepositoryException
     */
    public function create()
    {
        $this->walletRepository->pushCriteria(new EnabledCriteria());
        $wallet = $this->walletRepository->all()->pluck('extended_name', 'id');
        $hasCustomField = in_array($this->walletTransactionRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->walletTransactionRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('wallet_transactions.create')->with("customFields", isset($html) ? $html : false)->with("wallet", $wallet);
    }

    /**
     * Store a newly created WalletTransaction in storage.
     *
     * @param CreateWalletTransactionRequest $request
     *
     * @return Application|Redirector|RedirectResponse
     */
    public function store(CreateWalletTransactionRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = auth()->id();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->walletTransactionRepository->model());
        try {
            $walletTransaction = $this->walletTransactionRepository->create($input);
            $walletTransaction->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.wallet_transaction')]));

        return redirect(route('walletTransactions.index'));
    }
}
