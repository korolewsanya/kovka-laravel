<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

//Класс уведомления
class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
        //
    }

    //Указывает, какими каналами отправлять уведомление    'mail' — по email, 'sms' — по SMS (через сторонние сервисы)

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Новый заказ #' . $this->order->id)
            ->greeting('Здравствуйте!')
            ->line('Поступил новый заказ:')
            ->line('**Клиент:** ' . $this->order->customer_name)
            ->line('**Телефон:** ' . $this->order->customer_phone)
            ->line('**Email:** ' . $this->order->customer_email)
            ->line('**Товар:** ' . optional($this->order->product)->name ?? 'Не указан')
            ->line('**Сумма:** ' . number_format($this->order->price, 2, '.', ' ') . ' ₽')
            ->line('**Комментарий:** ' . ($this->order->comment ?? 'Нет'))
            ->action('Посмотреть заказ', url('/admin/orders/'))
            ->line('С уважением, Ковка');
    }
}