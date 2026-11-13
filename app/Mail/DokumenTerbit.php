<?php

namespace App\Mail;

use App\Models\PendaftaranMagang;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Email opsional (dipilih admin) saat surat balasan, surat selesai, atau sertifikat diterbitkan. PDF terlampir. */
class DokumenTerbit extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public int $tries = 3;

    /** jenis => [judul, kolom PDF, awalan nama lampiran] */
    private const JENIS = [
    'balasan'    => ['Surat Balasan Penerimaan Magang', 'file_surat_balasan', 'Surat_Balasan'],
    'selesai'    => ['Surat Keterangan Selesai Magang', 'file_surat_selesai', 'Surat_Selesai_Magang'],
    'sertifikat' => ['Sertifikat Magang', null, 'Sertifikat'],
];

    public function __construct(public PendaftaranMagang $pendaftaran, public string $jenis)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: self::JENIS[$this->jenis][0] . ' Anda Telah Terbit - ' . config('app.name'));
    }

    public function content(): Content
    {
        $p = $this->pendaftaran;

        return new Content(markdown: 'emails.dokumen', with: [
            'nama'     => $p->nama_lengkap,
            'judul'    => self::JENIS[$this->jenis][0],
            'kelompok' => $p->tipe === 'kelompok',
            'url'      => route('user.pendaftaran.status'),
        ]);
    }

    
public function attachments(): array
{
    $p    = $this->pendaftaran;
    $disk = Storage::disk(PendaftaranMagang::DISK_SURAT);

    [, $kolom, $awalan] = self::JENIS[$this->jenis];

    $berkas = $this->jenis === 'sertifikat'
        ? $p->sertifikatFiles->map(fn ($f) => [$f->file, $f->nama])->all()
        : [
            [
                $p->{$kolom},
                $awalan . '_' . Str::slug($p->nama_lengkap ?: 'peserta', '_') . '.pdf',
            ],
        ];

    $berkas = array_values(array_filter(
        $berkas,
        fn ($b) => filled($b[0]) && $disk->exists($b[0])
    ));

    // Jika total lampiran melebihi 20 MB, kirim email tanpa lampiran.
    if (array_sum(array_map(
        fn ($b) => $disk->size($b[0]),
        $berkas
    )) > 20 * 1024 * 1024) {
        return [];
    }

    return array_map(
        fn ($b) => Attachment::fromStorageDisk(
            PendaftaranMagang::DISK_SURAT,
            $b[0]
        )
            ->as($b[1])
            ->withMime('application/pdf'),
        $berkas
    );
}

}