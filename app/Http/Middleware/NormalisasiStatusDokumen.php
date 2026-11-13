<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Pilihan "Kirim Email" = terbitkan + kirim email. Validasi dokumen tetap hanya mengenal diproses/diterbitkan. */
class NormalisasiStatusDokumen
{
    private const KUNCI = ['status_surat', 'status_surat_selesai', 'status_sertifikat'];

    public function handle(Request $request, Closure $next)
    {
        foreach (self::KUNCI as $kunci) {
            if ($request->input($kunci) === 'kirim_email') {
                $request->merge([$kunci => 'diterbitkan', 'kirim_email' => '1']);
            }
        }

        return $next($request);
    }
}