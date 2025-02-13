<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $taskName;
    protected $projectName;

    public function __construct($taskName, $projectName)
    {
        $this->taskName = $taskName;
        $this->projectName = $projectName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Yeni Görev Atandı')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line("Yeni bir görev atandı: **{$this->taskName}**")
            ->line("Proje: **{$this->projectName}**")
            ->line('Detayları kontrol etmek için sistemimize giriş yapabilirsiniz.')
            ->action('Görevi Görüntüle', url('/tasks'))
            ->line('Teşekkürler!');
    }
}
