<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayrollGenerated extends Notification
{
    use Queueable;

    public function __construct(public $payroll)
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
            ->subject('Payslip Ready - ' . $this->payroll->period_start->format('M Y'))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your payslip for the period ' . $this->payroll->period_start->format('d M') . ' to ' . $this->payroll->period_end->format('d M') . ' is now available.')
            ->line('Net Salary: ' . number_format($this->payroll->net_salary, 2) . ' IDR')
            // ->action('View Payslip', route('filament.portal.resources.payslips.index')) // Assuming portal route
            ->line('Please log in to the Employee Portal to view details.');
    }
}
