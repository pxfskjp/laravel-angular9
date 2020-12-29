<?php

namespace App\Notifications;

use App\Data\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

final class RemindPasswordNotification extends AbstractNotification implements ShouldQueue
{
    use Queueable;

    /**
     *
     * @var string $token
     */
    private string $token;

    /**
     *
     * @param User $user
     * @param string $token
     */
    public function __construct(User $user, string $token)
    {
        parent::__construct($user);
        $this->token = $token;
    }

    /**
     *
     * @return MailMessage
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())->view('api::mail.remind-password', [
                'firstname' => $this->user->firstname,
                'remind_url' => app('url')->route('password.remind.view', ['token' => $this->token])
            ])->subject('Przypomnienie hasła');
    }
}
