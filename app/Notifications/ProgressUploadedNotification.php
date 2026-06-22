<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ProgressUploadedNotification extends Notification
{
    use Queueable;

    protected $progress;

    // Menerima data progress yang baru diupload
    public function __construct($progress)
    {
        $this->progress = $progress;
    }

    // Memberitahu Laravel untuk menyimpan notifikasi ini ke Database (MySQL)
    public function via($notifiable): array
    {
        return ['database'];
    }

    // Mengatur isi pesan notifikasi yang akan disimpan
    public function toArray($notifiable): array
    {
        return [
            'project_id' => $this->progress->project_id,
            'message' => 'Ada revisi/progress baru: "' . $this->progress->title . '" yang diunggah oleh ' . auth()->user()->name,
            'time' => now()->format('H:i')
        ];
    }
}