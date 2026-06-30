<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class NewCustomerNotification extends Notification
{
    use Queueable;

    public function __construct(protected User $customer) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'New customer registered: ' . $this->customer->name,
            'details' => 'Email: ' . $this->customer->email,
            'customer_id' => $this->customer->id,
            'url'     => '/admin/customers/' . $this->customer->id,
        ];
    }
}
