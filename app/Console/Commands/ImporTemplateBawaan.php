<?php

namespace App\Console\Commands;

use App\Jobs\BuatPratinjauTemplate;
use App\Models\TemplateSurat;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PhpOffice\PhpWord\TemplateProcessor;

class ImporTemplateBawaan extends Command
{
    protected $signature = 'template:impor-bawaan {--paksa : ganti template bawaan yang sudah ada}';
    protected $description = 'Menyalin template surat dari resources/templates menjadi template bawaan dan membuat contoh hasilnya.';

    public function handle(): int
    {
        foreach (TemplateSurat::SLOT as $jenis => $slot) {
            $sumber = (string) config('surat.template.' . $slot['config']);

            if (! is_file($sumber)) {
                $this->warn("Lewati {$jenis}: file tidak ditemukan ({$sumber})");
                continue;
            }

            $lama = TemplateSurat::whereNull('dinas_id')->where('jenis', $jenis)->get();

            if ($lama->isNotEmpty() && ! $this->option('paksa')) {
                $this->line("Lewati {$jenis}: sudah ada");
                continue;
            }

            $lama->each->delete();

            $path = 'templates/bawaan/' . Str::uuid() . '.docx';
            Storage::disk('local')->put($path, file_get_contents($sumber));

            $t = TemplateSurat::create([
                'dinas_id'      => null,
                'jenis'         => $jenis,
                'status'        => 'aktif',
                'file'          => $path,
                'nama_asli'     => basename($sumber),
                'kode'          => array_values(array_unique((new TemplateProcessor($sumber))->getVariables())),
                'diaktifkan_at' => now(),
            ]);

            BuatPratinjauTemplate::dispatch($t);
            $this->info("Diimpor: {$jenis}");
        }

        return self::SUCCESS;
    }
}