<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProgressUploadedNotification extends Notification
{
    use Queueable;

    protected $details;

    // 1. Pastikan Constructor menerima variabel $details
    public function __construct($details)
    {
        $this->details = $details;
    }

    // 2. PASTIKAN DI SINI ADA 'mail' DAN 'database'
    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    // 3. PASTIKAN JALUR EMAILNYA SUDAH DIRAKIT SEPERTI INI
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('🎬 Undangan Bergabung Proyek FilmInOne')
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line($this->details['message'])
                    ->action('Buka Ruang Kerja', url('/project/' . $this->details['project_id']))
                    ->line('Selamat berkarya di platform FilmInOne!');
    }

    // 4. Ini untuk notifikasi internal di dalam website (Abaikan/Biarkan tetap ada)
    public function toArray($notifiable)
    {
        return [
            'message' => $this->details['message'],
            'project_id' => $this->details['project_id']
        ];
    }
}