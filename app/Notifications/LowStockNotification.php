<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Product;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(protected Product $product) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low Stock Alert: ' . $this->product->name)
            ->greeting('Stock Alert!')
            ->line($this->product->name . ' is running low on stock.')
            ->line('Current stock: ' . $this->product->stock . ' units.')
            ->action('View Product', url('/admin/products/' . $this->product->id . '/edit'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Low stock: ' . $this->product->name,
            'details' => 'Only ' . $this->product->stock . ' units remaining.',
            'product_id' => $this->product->id,
            'url'     => '/admin/products/' . $this->product->id . '/edit',
        ];
    }
}
