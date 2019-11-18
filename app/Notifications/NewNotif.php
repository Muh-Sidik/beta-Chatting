<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\User;
use App\Chat;
use App\DetailChat;


class NewNotif extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    protected $user;
    protected $chat;
    protected $detailChat;
    
    public function __construct(User $user, Chat $chat, DetailChat $detailChat)
    {
        $this->user = $user;
        $this->chat = $chat;
        $this->detailChat = $detailChat;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'user_id' => $this->user->id,
            'username'=> $this->user->username,
            'chat' => $this->detailChat->chat,
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'user_id' => $this->user->id,
            'username'=> $this->user->username,
            'chat' => $this->detailChat->chat,
        ]);
    }

    public function notifications($id)
    {
        $user = App\User::find($id);

        foreach ($user->notifications as $notification) {
        echo $notification->type;
        }
    }

    public function unreadNotifications($id)
    {
        $user = User::find($id);

        foreach ($user->unreadNotifications as $notification) {
            echo $notification->type;
        }
    }

    public function chatreadAt($id)
    {
        $user = App\User::find($id);

        $user->unreadNotifications()->update(['read_at' => now()]);
    }

    public function chatDelete($id)
    {
        $user = App\User::find($id);

        $user->notifications()->delete();
    }

}
