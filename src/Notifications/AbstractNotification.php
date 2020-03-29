<?php

namespace App\Notification;

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

    public function via($notifiable)
    {
        return [
            'mail'
        ];
    }

    public function toArray($notifiable)
    {
        return [];
    }
}
