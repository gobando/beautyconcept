<?php
/*
 * File name: SalonReviewController.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Http\Controllers;

use App\Criteria\SalonReviews\SalonReviewsOfUserCriteria;
use App\Criteria\Users\SalonsCriteria;
use App\DataTables\SalonReviewDataTable;
use App\Http\Requests\CreateSalonReviewRequest;
use App\Http\Requests\UpdateSalonReviewRequest;
use App\Repositories\CustomFieldRepository;
use App\Repositories\SalonReviewRepository;
use App\Repositories\UserRepository;
use Flash;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

class SalonReviewController extends Controller
{
    /** @var  SalonReviewRepository */
    private $salonReviewRepository;

    /**
     * @var CustomFieldRepository
     */
    private $customFieldRepository;


    public function __construct(SalonReviewRepository $salonReviewRepo, CustomFieldRepository $customFieldRepo)
    {
        parent::__construct();
        $this->salonReviewRepository = $salonReviewRepo;
        $this->customFieldRepository = $customFieldRepo;
    }

    /**
     * Display a listing of the SalonReview.
     *
     * @param SalonReviewDataTable $salonReviewDataTable
     * @return Response
     */
    public function index(SalonReviewDataTable $salonReviewDataTable)
    {
        return $salonReviewDataTable->render('salon_reviews.index');
    }

    /**
     * Store a newly created SalonReview in storage.
     *
     * @param CreateSalonReviewRequest $request
     *
     * @return Application|Redirector|RedirectResponse
     */
    public function store(CreateSalonReviewRequest $request)
    {
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->salonReviewRepository->model());
        try {
            $salonReview = $this->salonReviewRepository->create($input);
            $salonReview->customFieldsValues()->createMany(getCustomFieldsValues($customFields, $request));

        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }

        Flash::success(__('lang.saved_successfully', ['operator' => __('lang.salon_review')]));

        return redirect(route('salonReviews.index'));
    }

    /**
     * Display the specified SalonReview.
     *
     * @param int $id
     *
     * @return Application|Factory|Redirector|RedirectResponse|View
     * @throws RepositoryException
     */
    public function show(int $id)
    {
        $this->salonReviewRepository->pushCriteria(new SalonReviewsOfUserCriteria(auth()->id()));
        $salonReview = $this->salonReviewRepository->findWithoutFail($id);

        if (empty($salonReview)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.salon_review')]));
            return redirect(route('salonReviews.index'));
        }
        return view('salon_reviews.show')->with('salonReview', $salonReview);
    }

    /**
     * Show the form for editing the specified SalonReview.
     *
     * @param int $id
     *
     * @return Application|Factory|Redirector|RedirectResponse|View
     * @throws RepositoryException
     */
    public function edit(int $id)
    {
        $this->salonReviewRepository->pushCriteria(new SalonReviewsOfUserCriteria(auth()->id()));
        $salonReview = $this->salonReviewRepository->findWithoutFail($id);
        if (empty($salonReview)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.salon_review')]));
            return redirect(route('salonReviews.index'));
        }

        $customFieldsValues = $salonReview->customFieldsValues()->with('customField')->get();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->salonReviewRepository->model());
        $hasCustomField = in_array($this->salonReviewRepository->model(), setting('custom_field_models', []));
        if ($hasCustomField) {
            $html = generateCustomField($customFields, $customFieldsValues);
        }
        return view('salon_reviews.edit')->with('salonReview', $salonReview)->with("customFields", $html ?? false);
    }

    /**
     * Update the specified SalonReview in storage.
     *
     * @param int $id
     * @param UpdateSalonReviewRequest $request
     *
     * @return Application|Redirector|RedirectResponse
     * @throws RepositoryException
     */
    public function update(int $id, UpdateSalonReviewRequest $request)
    {
        $this->salonReviewRepository->pushCriteria(new SalonReviewsOfUserCriteria(auth()->id()));
        $salonReview = $this->salonReviewRepository->findWithoutFail($id);

        if (empty($salonReview)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.salon_review')]));
            return redirect(route('salonReviews.index'));
        }
        $input = $request->all();
        $customFields = $this->customFieldRepository->findByField('custom_field_model', $this->salonReviewRepository->model());
        try {
            $salonReview = $this->salonReviewRepository->update($input, $id);

            foreach (getCustomFieldsValues($customFields, $request) as $value) {
                $salonReview->customFieldsValues()
                    ->updateOrCreate(['custom_field_id' => $value['custom_field_id']], $value);
            }
        } catch (ValidatorException $e) {
            Flash::error($e->getMessage());
        }
        Flash::success(__('lang.updated_successfully', ['operator' => __('lang.salon_review')]));
        return redirect(route('salonReviews.index'));
    }

    /**
     * Remove the specified SalonReview from storage.
     *
     * @param int $id
     *
     * @return Application|Redirector|RedirectResponse
     * @throws RepositoryException
     */
    public function destroy(int $id)
    {
        $this->salonReviewRepository->pushCriteria(new SalonReviewsOfUserCriteria(auth()->id()));
        $salonReview = $this->salonReviewRepository->findWithoutFail($id);

        if (empty($salonReview)) {
            Flash::error(__('lang.not_found', ['operator' => __('lang.salon_review')]));
            return redirect(route('salonReviews.index'));
        }

        $this->salonReviewRepository->delete($id);

        Flash::success(__('lang.deleted_successfully', ['operator' => __('lang.salon_review')]));
        return redirect(route('salonReviews.index'));
    }

}
