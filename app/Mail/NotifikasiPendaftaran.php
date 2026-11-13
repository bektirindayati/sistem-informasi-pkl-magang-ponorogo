<?php

namespace App\Mail;

use App\Models\PendaftaranMagang;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiPendaftaran extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(public PendaftaranMagang $pendaftaran)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat! Pendaftaran Magang Anda Diterima - ' . config('app.name')
        );
    }

    public function content(): Content
    {
        $p = $this->pendaftaran;

        $tgl = fn ($d) => $d
            ? Carbon::parse($d)->locale('id')->translatedFormat('j F Y')
            : '-';

        return new Content(
            markdown: 'emails.diterima',
            with: [
                'p'       => $p,
                'nama'    => $p->nama_lengkap,
                'periode' => $tgl($p->tanggal_mulai) . ' s.d. ' . $tgl($p->tanggal_selesai),
                'url'     => route('user.pendaftaran.status'),
            ],
        );
    }
}
