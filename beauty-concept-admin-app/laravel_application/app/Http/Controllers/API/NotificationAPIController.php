<?php
/*
 * File name: NotificationAPIController.php
 * Last modified: 2021.09.15 at 13:28:01
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Http\Controllers\API;


use App\Criteria\Notifications\UnReadCriteria;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Notifications\NewMessage;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Exceptions\RepositoryException;
use Prettus\Validator\Exceptions\ValidatorException;

/**
 * Class NotificationController
 * @package App\Http\Controllers\API
 */
class NotificationAPIController extends Controller
{
    /** @var  NotificationRepository */
    private $notificationRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(NotificationRepository $notificationRepo, UserRepository $userRepository)
    {
        $this->notificationRepository = $notificationRepo;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    /**
     * Display a listing of the Notification.
     * GET|HEAD /notifications
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $this->notificationRepository->pushCriteria(new RequestCriteria($request));
            $this->notificationRepository->pushCriteria(new LimitOffsetCriteria($request));
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage(), 200);
        }
        $notifications = $this->notificationRepository->all();
        $this->filterCollection($request, $notifications);

        return $this->sendResponse($notifications->toArray(), 'Notifications retrieved successfully');
    }

    /**
     * Display a count of Notifications.
     * GET|HEAD /notifications/count
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function count(Request $request): JsonResponse
    {
        try {
            $this->notificationRepository->pushCriteria(new RequestCriteria($request));
            $this->notificationRepository->pushCriteria(new UnReadCriteria());
        } catch (RepositoryException $e) {
            return $this->sendError($e->getMessage(), 200);
        }
        $count = $this->notificationRepository->count();

        return $this->sendResponse($count, 'Notifications count retrieved successfully');
    }

    /**
     * Store a newly created Notification in storage.
     * POST /notifications
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $usersId = $request->get('users');
            $fromId = $request->get('from');
            $text = $request->get('text');
            $messageId = $request->get('id');
            $users = $this->userRepository->findWhereIn('id', $usersId);
            $from = $this->userRepository->find($fromId);
            \Illuminate\Support\Facades\Notification::send($users, new NewMessage($from, $text, $messageId));
        } catch (Exception $e) {
            return $this->sendError($e->getMessage());
        }
        return $this->sendResponse(true, __('lang.saved_successfully', ['operator' => __('lang.notification')]));
    }

    /**
     * Display the specified Notification.
     * GET|HEAD /notifications/{id}
     *
     * @param $id
     *
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        /** @var Notification $notification */
        if (!empty($this->notificationRepository)) {
            $notification = $this->notificationRepository->findWithoutFail($id);
        }

        if (empty($notification)) {
            return $this->sendError('Notification not found', 200);
        }

        return $this->sendResponse($notification->toArray(), 'Notification retrieved successfully');
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
        $notification = $this->notificationRepository->findWithoutFail($id);

        if (empty($notification)) {
            return $this->sendError('Notification not found', 200);
        }
        $input = $request->all();

        if (isset($input['read_at'])) {
            if ($input['read_at'] == true) {
                $input['read_at'] = Carbon::now();
            } else {
                unset($input['read_at']);
            }
        }
        try {
            $notification = $this->notificationRepository->update($input, $id);

        } catch (ValidatorException $e) {
            return $this->sendError($e->getMessage(), 200);
        }

        return $this->sendResponse($notification->toArray(), __('lang.saved_successfully', ['operator' => __('lang.notification')]));
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
        $notification = $this->notificationRepository->findWithoutFail($id);

        if (empty($notification)) {
            return $this->sendError('Notification not found', 200);
        }

        if ($this->notificationRepository->delete($id) < 1) {
            $this->sendError('Notification not deleted', 200);
        }

        return $this->sendResponse($notification, __('lang.deleted_successfully', ['operator' => __('lang.notification')]));

    }
}
