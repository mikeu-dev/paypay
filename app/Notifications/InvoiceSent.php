<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceSent extends Notification
{
    use Queueable;

    public function __construct(public $invoice)
    {
        //
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Invoice #' . $this->invoice->number . ' from PayPay ERP')
            ->greeting('Dear ' . $notifiable->name . ',')
            ->line('Please find attached invoice #' . $this->invoice->number . ' for ' . $this->invoice->currency . ' ' . number_format($this->invoice->total, 2) . '.')
            ->action('View Invoice', route('invoices.print', $this->invoice))
            ->line('Thank you for your business!');
    }
}
