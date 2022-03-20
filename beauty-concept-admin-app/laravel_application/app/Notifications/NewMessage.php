<?php
/*
 * File name: NewMessage.php
 * Last modified: 2021.11.01 at 22:25:44
 * Author: SmarterVision - https://codecanyon.net/user/smartervision
 * Copyright (c) 2021
 */

namespace App\Notifications;

use App\Models\User;
use Benwilkins\FCM\FcmMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewMessage extends Notification
{
    use Queueable;

    /**  @var User */
    private $user;

    /** @var string */
    private $text;

    /** @var string */
    private $messageId;


    /**
     * Create a new notification instance.
     *
     * @param User $user
     * @param string $text
     * @param string $messageId
     */
    public function __construct(User $user, string $text, string $messageId)
    {
        $this->user = $user;
        $this->text = $text;
        $this->messageId = $messageId;
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
        return $types;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the fcm representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */

    public function toFcm($notifiable): FcmMessage
    {
        $message = new FcmMessage();
        $notification = [
            'title' => $this->user->name . " " . __("lang.notification_sent_new_message"),
            'body' => $this->text,
            'icon' => $this->getUserAvatarUrl(),
            'click_action' => "FLUTTER_NOTIFICATION_CLICK",
            'id' => 'App\\Notifications\\NewMessage',
            'status' => 'done',
        ];
        $data = $notification;
        $data['messageId'] = $this->messageId;
        $message->content($notification)->data($data)->priority(FcmMessage::PRIORITY_HIGH);
        return $message;
    }

    private function getUserAvatarUrl(): string
    {
        if ($this->user->hasMedia('avatar')) {
            return $this->user->getFirstMediaUrl('avatar', 'thumb');
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
    public function toArray($notifiable): array
    {
        return [
            'from' => $this->user->name,
            'message_id' => $this->messageId,
        ];
    }
}
