<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProductNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $productName;

    public function __construct($productName)
    {
        $this->productName = $productName;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Yeni Ürün Eklendi')
            ->greeting('Merhaba ' . $notifiable->name . ',')
            ->line("Yeni bir ürün eklendi: **{$this->productName}**")
            ->line('Detayları kontrol etmek için sistemimize giriş yapabilirsiniz.')
            ->action('Ürünü Görüntüle', url('/products'))
            ->line('Teşekkürler!');
    }
}
