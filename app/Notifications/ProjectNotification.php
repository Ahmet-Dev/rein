<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjectNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $projectName;

    public function __construct($projectName)
    {
        $this->projectName = $projectName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Yeni Proje Oluşturuldu')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line("Yeni bir proje oluşturuldu: **{$this->projectName}**")
            ->line('Detayları kontrol etmek için sistemimize giriş yapabilirsiniz.')
            ->action('Projeyi Görüntüle', url('/projects'))
            ->line('Teşekkürler!');
    }
}

