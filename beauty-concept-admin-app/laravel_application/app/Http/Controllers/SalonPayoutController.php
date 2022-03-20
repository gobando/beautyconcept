<?php
/*
 * File name: SalonPayoutController.php
 * Last modified: 2022.02.02 at 21:22:03
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Controllers;

use App\Criteria\Salons\SalonsOfUserCriteria;
use App\DataTables\SalonPayoutDataTable;
use App\Http\Requests\CreateSalonPayoutRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\EarningRepository;
use App\Repositories\SalonPayoutRepository;
use App\Repositories\SalonRepository;
use Carbon\Carbon;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

class SalonPayoutController extends Controller
{
    /** @var  SalonPayoutRepository */
    private $salonPayoutRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;

    /**
     * @var SalonRepository
     */
    private $salonRepository;
    /**
     * @var EarningRepository
     */
    private $earningRepository;

    public function __construct(SalonPayoutRepository $salonPayoutRepo, CustomFieldRepository $customFieldRepo, SalonRepository $salonRepo, EarningRepository $earningRepository)
    {
        parent::__construct();
        $this->salonPayoutRepository = $salonPayoutRepo;
        $this->customFieldRepository = $customFieldRepo;
        $this->salonRepository = $salonRepo;
        $this->earningRepository = $earningRepository;
    }

    /**
     * Display a listing of the SalonPayout.
     *
     * @param SalonPayoutDataTable $salonPayoutDataTable
     * @return Response
     */
    public function index(SalonPayoutDataTable $salonPayoutDataTable)
    {
        return $salonPayoutDataTable->render('salon_payouts.index');
    }

    /**
     * Show the form for creating a new SalonPayout.
     *
     * @param int $id
     * @return Application|Factory|Response|View
     * @throws RepositoryException
     */
    public function create(int $id)
    {
        $this->salonRepository->pushCriteria(new SalonsOfUserCriteria(auth()->id()));
        $salon = $this->salonRepository->findWithoutFail($id);
        if (empty($salon)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.salon')]));
            return redirect(route('salonPayouts.index'));
        }
        $earning = $this->earningRepository->findByField('salon_id', $id)->first();
        $totalPayout = $this->salonPayoutRepository->findByField('salon_id', $id)->sum("amount");

        $hasCustomField = in_array($this->salonPayoutRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->salonPayoutRepository->model());
            $html = generateCustomField($customFields);
        }
        return view('salon_payouts.create')->with("customFields", isset($html) ? $html : false)->with("salon", $salon)->with("amount", $earning->salon_earning - $totalPayout);
    }

    /**
     * Store a newly created SalonPayout in storage.
     *
     * @param CreateSalonPayoutRequest $request
     *
     * @return Application|RedirectResponse|Redirector|Response
     */
    public function store(CreateSalonPayoutRequest $request)
    {
        $input = $request->all();
        $earning = $this->earningRepository->findByField('salon_id', $input['salon_id'])->first();
        $totalPayout = $this->salonPayoutRepository->findByField('salon_id', $input['salon_id'])->sum("amount");
        $input['amount'] = $earning->salon_earning - $totalPayout;
        if ($input['amount'] <= 0) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.earning')]));
            return redirect(route('salonPayouts.index'));
        }
        $input['paid_date'] = Carbon::now();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->salonPayoutRepository->model());
        try {
            $salonPayout = $this->salonPayoutRepository->create($input);
            $salonPayout->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.salon_payout')]));

        return redirect(route('salonPayouts.index'));
    }
}
