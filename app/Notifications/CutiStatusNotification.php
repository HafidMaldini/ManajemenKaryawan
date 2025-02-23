<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class CutiStatusNotification extends Notification
{
    use Queueable;

    private $cuti;
    private $status;

    public function __construct($cuti, $status)
    {
        $this->cuti = $cuti;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database', 'mail']; // Simpan di database & kirim email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Status Pengajuan Cuti')
            ->greeting('Halo, ' . $notifiable->name)
            ->line("Pengajuan cuti Anda pada mulai tanggal {$this->cuti->tanggal_mulai} hingga tanggal {$this->cuti->tanggal_selesai} telah {$this->status}.")
            ->action('Lihat Detail', url('/cuti'))
            ->line('Terima kasih telah menggunakan sistem kami!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Pengajuan cuti Anda pada mulai {$this->cuti->tanggal_mulai} hingga {$this->cuti->tanggal_selesai} telah {$this->status}.",
            'cuti_id' => $this->cuti->id,
            'status' => $this->status,
        ];
    }
}
