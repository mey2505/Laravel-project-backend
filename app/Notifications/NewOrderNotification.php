<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(protected Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Order: #' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new order has been placed.')
            ->line('Order #: ' . $this->order->order_number)
            ->line('Total: $' . number_format($this->order->total, 2))
            ->action('View Order', url('/admin/orders/' . $this->order->id));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New order #' . $this->order->order_number . ' received.',
            'details' => 'Total: $' . number_format($this->order->total, 2),
            'order_id' => $this->order->id,
            'url'     => '/admin/orders/' . $this->order->id,
        ];
    }
}
