<?php

namespace App\Notifications;

use App\Data\Models\User;
use Illuminate\Notifications\Notification;

abstract class AbstractNotification extends Notification
{

    /**
     *
     * @var User $user
     */
    protected $user;

    /**
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     *
     * @param mixed $notifiable
     * @return string[]
     */
    public function via($notifiable)
    {
        return [
            'mail'
        ];
    }

    /**
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
