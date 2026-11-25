<?php

namespace App\Jobs;

use App\Models\TemplateSurat;
use App\Services\Surat\ContohSurat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/** Membuat PDF contoh hasil sebuah template. Gagal (mis. tanpa LibreOffice) tidak mengganggu fitur lain. */
class BuatPratinjauTemplate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 1;
    public int $timeout = 150;

    public function __construct(public TemplateSurat $template)
    {
    }

    public function handle(ContohSurat $contoh): void
    {
        $t = $this->template->fresh();

        if (! $t) {
            return;
        }

        try {
            $docx = $contoh->docx($t->jenis, Storage::disk('local')->path($t->file), $t->dinas);
            $pdf  = $contoh->pdf($docx);
            @unlink($docx);

            $tujuan = 'templates/pratinjau/' . $t->id . '.pdf';
            Storage::disk('local')->put($tujuan, $pdf);

            $t->update(['pratinjau' => $tujuan, 'pratinjau_status' => 'siap', 'pratinjau_pesan' => null]);
        } catch (\Throwable $e) {
            report($e);

            $t->update(['pratinjau_status' => 'gagal', 'pratinjau_pesan' => Str::limit($e->getMessage(), 400)]);
        }
    }
}