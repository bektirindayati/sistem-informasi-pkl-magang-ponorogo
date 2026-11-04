<?php

namespace App\Mail;

use App\Models\PendaftaranMagang;
use Carbon\Carbon;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Str;

/** Email ke pendaftar: diterima | surat (PDF terlampir) | sertifikat (PDF terlampir). Isi: resources/views/emails/. */
class NotifikasiPendaftaran extends Mailable
{
    private const JUDUL = [
        'diterima'   => 'Selamat! Pendaftaran Magang Anda Diterima',
        'surat'      => 'Surat Balasan Penerimaan Magang Anda Telah Terbit',
        'sertifikat' => 'Sertifikat Magang Anda Telah Terbit',
    ];

    /** Jenis => [kolom PDF, awalan nama lampiran]. */
    private const LAMPIRAN = [
        'surat'      => ['file_surat_balasan', 'Surat_Balasan'],
        'sertifikat' => ['file_sertifikat', 'Sertifikat'],
    ];

    public function __construct(public PendaftaranMagang $pendaftaran, public string $jenis)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: self::JUDUL[$this->jenis] . ' - ' . config('app.name'));
    }

    public function content(): Content
    {
        $p = $this->pendaftaran;
       $format = fn ($tanggal) => $tanggal
    ? Carbon::parse($tanggal)->locale('id')->translatedFormat('j F Y')
    : '-';

        return new Content(
            markdown: 'emails.' . $this->jenis,
            with: [
                'p'       => $p,
                'nama'    => $p->nama_lengkap,
                'periode' => $format($p->tanggal_mulai) . ' s.d. ' . $format($p->tanggal_selesai),
                'url'     => route('user.pendaftaran.status'),
            ],
        );
    }

    public function attachments(): array
    {
        [$kolom, $awalan] = self::LAMPIRAN[$this->jenis] ?? [null, null];
        $path = $kolom ? $this->pendaftaran->{$kolom} : null;

        if (blank($path)) {
            return [];
        }

        return [
            Attachment::fromStorageDisk(PendaftaranMagang::DISK_SURAT, $path)
                ->as($awalan . '_' . Str::slug($this->pendaftaran->nama_lengkap ?: 'peserta', '_') . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}