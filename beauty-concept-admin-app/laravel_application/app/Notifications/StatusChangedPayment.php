<?php
/*
 * File name: StatusChangedPayment.php
 * Last modified: 2022.02.12 at 02:17:42
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2022
 */

namespace App\Notifications;

use App\Models\Booking;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatusChangedPayment extends Notification
{
    use Queueable;

    /**
     * @var Booking
     */
    private $booking;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $types = ['database'];
        if (setting('enable_notifications', false)) {
            array_push($types, 'fcm');
        }
        if (setting('enable_email_notifications', false)) {
            array_push($types, 'mail');
        }
        return $types;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(trans('lang.notification_payment', ['booking_id' => $this->booking->id, 'payment_status' => $this->booking->payment->paymentStatus->status]) . " | " . setting('app_name', ''))
            ->markdown("notifications::booking", ['booking' => $this->booking])
            ->greeting(trans('lang.notification_payment', ['booking_id' => $this->booking->id, 'payment_status' => $this->booking->payment->paymentStatus->status]))
            ->action(trans('lang.booking_details'), route('bookings.show', $this->booking->id));
    }

    public function toFcm($notifiable): FcmMessage
    {
        $message = new FcmMessage();
        $notification = [
            'body' => trans('lang.notification_payment', ['booking_id' => $this->booking->id, 'payment_status' => $this->booking->payment->paymentStatus->status]),
            'title' => trans('lang.notification_status_changed_payment'),
            'icon' => $this->getSalonMediaUrl(),
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => 'App\\Notifications\\StatusChangedPayment',
            'status' => 'done',
        ];
        $data = $notification;
        $data['bookingId'] = $this->booking->id;
        $message->content($notification)->data($data)->priority(FcmMessage::PRIORITY_HIGH);

        return $message;
    }

    private function getSalonMediaUrl(): string
    {
        if ($this->booking->salon->hasMedia('image')) {
            return $this->booking->salon->getFirstMediaUrl('image', 'thumb');
        } else {
            return asset('images/image_default.png');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'booking_id' => $this->booking['id'],
        ];
    }
}
