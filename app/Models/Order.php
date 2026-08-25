<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Notifications\NewOrderNotification;

class Order extends Model
{
    protected $fillable = [
        'product_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'comment',
        'order_date',
        'price',
        'paid',
        'progress',
        'length',
        'width',
        'height',
        'status',
        'image',
        'paid',
        'progress',
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'paid' => 'decimal:2',
        'order_date' => 'date',
    ];
    
    // Связь с товаром (один заказ принадлежит одному товару)
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    //Отправка уведомлений при создании заказа
    protected static function booted()  //инициализации модели, добавяем события
    {
        static::created(function ($order) {     //Событие created — срабатывает после того, как новый заказ сохранен в базу данных.
            $admins = User::where('role', 'admin')->get();    //Находит всех пользователей с ролью admin в базе данных
            foreach ($admins as $admin) {
                $admin->notify(new NewOrderNotification($order));  //$admin->notify() — метод у модели User, который отправляет уведомление
                                                                    //new NewOrderNotification($order) — создает объект уведомления, передавая в него заказ
            }
        });
    }
}