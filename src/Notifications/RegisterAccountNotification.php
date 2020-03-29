<?php
namespace App\Notification;

use App\Data\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

final class RegisterAccountNotification extends AbstractNotification implements ShouldQueue
{
    use Queueable;

    /**
     *
     * @var string $token
     */
    private $token;

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
    public function toMail($notifable): MailMessage
    {
        return (new MailMessage())->view('api::mail.register-account', [
                'firstname' => $this->user->firstname,
                'confirm_url' => app('url')->route('confirm.register', ['token' => $this->token])
            ])->subject('Rejestracja konta');
    }
}
