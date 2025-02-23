<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class TugasStatusNotification extends Notification
{
    use Queueable;

    private $tugas;
    private $status;
    private $user;

    public function __construct($tugas, $status, $user)
    {
        $this->tugas = $tugas;
        $this->status = $status;
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toMail($notifiable)
    {
        // dd($this->tugas->title);
        return (new MailMessage)
            ->subject('Status Tugas')
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Tugas '{$this->tugas->title}' telah {$this->status} oleh {$this->user->name}.")
            ->action('Lihat Detail', url('/tugas'))
            ->line('Terima kasih telah menggunakan sistem kami!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Tugas '{$this->tugas->title}' telah {$this->status} oleh {$this->user->name}.",
            'tugas_id' => $this->tugas->id,
            'status' => $this->status,
            'updated_by' => $this->user->name,
        ];
    }
}
