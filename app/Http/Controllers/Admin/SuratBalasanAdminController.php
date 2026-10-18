<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\SuratBalasanException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SuratBalasan\AksesSuratBalasanRequest;
use App\Http\Requests\Admin\SuratBalasan\TerbitkanSuratBalasanRequest;
use App\Http\Requests\Admin\SuratBalasan\UpdateSuratBalasanRequest;
use App\Services\Surat\SuratBalasanService;
use Illuminate\Support\Facades\Auth;

/**
 * Surat balasan untuk pendaftar yang diterima:
 * isi data -> pratinjau draf PDF -> terbitkan -> peserta mengunduh.
 */
class SuratBalasanAdminController extends Controller
{
    public function __construct(private SuratBalasanService $surat)
    {
    }

    /** Halaman kerja: form data surat, pratinjau, dan tombol terbitkan. */
    public function show(AksesSuratBalasanRequest $request)
    {
        $pendaftaran = $request->pendaftaran();

        return view('admin.surat.show', [
            'pendaftaran' => $pendaftaran,
            'surat'       => $this->surat->ambilAtauBuat($pendaftaran),
            'wajibTtd'    => (bool) config('surat.wajib_unggah_ttd'),
        ]);
    }

    /** Simpan data surat dan buat ulang draf PDF. */
    public function update(UpdateSuratBalasanRequest $request)
    {
        $surat = $this->surat->ambilAtauBuat($request->pendaftaran());

        if ($surat->sudahTerbit()) {
            return back()->with('error', 'Surat sudah diterbitkan. Tarik penerbitan terlebih dahulu untuk mengubah datanya.');
        }

        try {
            $this->surat->perbarui($surat, $request->dataSurat());
        } catch (SuratBalasanException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Data surat disimpan dan draf PDF diperbarui. Silakan periksa pratinjau.');
    }

    /**
     * PDF untuk dilihat di halaman (inline) atau diunduh (?unduh=1).
     * Surat yang sudah terbit menampilkan file final, selain itu draf.
     */
    public function pratinjau(AksesSuratBalasanRequest $request)
    {
        $pendaftaran = $request->pendaftaran();
        $surat = $pendaftaran->suratBalasan;

        $path = $surat?->sudahTerbit() ? $surat->file_final : $surat?->file_draf;

        abort_unless($path, 404, 'Draf surat belum dibuat.');

        return $this->surat->responsPdf(
            $path,
            $this->surat->namaFile($pendaftaran, $surat->sudahTerbit() ? 'Surat_Balasan' : 'Draf_Surat_Balasan'),
            $request->boolean('unduh') ? 'attachment' : 'inline'
        );
    }

    public function terbitkan(TerbitkanSuratBalasanRequest $request)
    {
        $surat = $request->pendaftaran()->suratBalasan;

        try {
            $this->surat->terbitkan($surat, $request->file('pdf_bertanda_tangan'), Auth::user());
        } catch (SuratBalasanException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Surat balasan diterbitkan. Peserta sekarang dapat mengunduhnya dari halaman status.');
    }

    /** Batalkan penerbitan: surat kembali menjadi draf dan tidak bisa diunduh peserta. */
    public function tarik(AksesSuratBalasanRequest $request)
    {
        $surat = $request->pendaftaran()->suratBalasan;

        abort_unless($surat?->sudahTerbit(), 404);

        $this->surat->tarik($surat);

        return back()->with('success', 'Penerbitan ditarik. Surat kembali menjadi draf dan tidak lagi dapat diunduh peserta.');
    }
}