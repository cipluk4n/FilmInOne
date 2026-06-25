<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RevisionUrgentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $progressDetail;

    public function __construct($project, $progressDetail)
    {
        $this->project = $project;
        $this->progressDetail = $progressDetail;
    }

    public function build()
    {
        return $this->subject('🚨 REVISI PENTING: Proyek ' . $this->project->title)
                    ->html("
                        <h3>Pemberitahuan Revisi Penting!</h3>
                        <p>Halo, ada pembaruan progress penting yang membutuhkan perhatian Anda pada proyek: <strong>{$this->project->title}</strong></p>
                        <p><strong>Detail Progress / Catatan Revisi:</strong></p>
                        <blockquote style='background: #f4f4f4; padding: 10px; border-left: 5px solid #dc3545;'>
                            {$this->progressDetail}
                        </blockquote>
                        <p>Silakan login ke aplikasi <strong>FilmInOne</strong> untuk melihat detail selengkapnya.</p>
                    ");
    }
}