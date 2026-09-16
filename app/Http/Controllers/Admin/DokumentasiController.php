<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DokumentasiMagang;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    private function rules(bool $fotoRequired): array
    {
        return [
            'judul_kegiatan' => ['required', 'string', 'max:255'],
            'kategori_badge' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'foto' => [
                $fotoRequired ? 'required' : 'nullable',
                'image',
                'max:2048',
            ],
        ];
    }

    private function messages(): array
    {
        return [
            'judul_kegiatan.required' => 'Judul kegiatan wajib diisi.',
            'judul_kegiatan.max' => 'Judul kegiatan maksimal 255 karakter.',

            'kategori_badge.required' => 'Kategori / sub-judul wajib diisi.',
            'kategori_badge.max' => 'Kategori / sub-judul maksimal 255 karakter.',

            'deskripsi.required' => 'Uraian / deskripsi kegiatan wajib diisi.',

            'foto.required' => 'Foto dokumentasi wajib diunggah.',
            'foto.image' => 'File yang diunggah harus berupa gambar.',
            'foto.max' => 'Ukuran foto tidak boleh lebih dari 2MB.',
        ];
    }

    /**
     * Tampilkan daftar dokumentasi + form tambah baru (mode default).
     */
    public function index()
    {
        $dokumentasis = DokumentasiMagang::latest()->get();

        return view('admin.dokumentasi.index', [
            'dokumentasis' => $dokumentasis,
            'editItem' => null,
        ]);
    }

    /**
     * Simpan dokumentasi baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            $this->rules(fotoRequired: true),
            $this->messages()
        );

        $validated['foto'] = $this->uploadFoto($request->file('foto'));

        DokumentasiMagang::create($validated);

        return redirect()
            ->route('admin.dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil ditambahkan dan langsung tampil di halaman utama.');
    }

    /**
     * Tampilkan form edit -- pakai view yang sama dengan index(), cuma
     * dikirimi $editItem supaya form berubah ke mode edit (lihat
     * admin/dokumentasi/index.blade.php).
     */
    public function edit($id)
    {
        $editItem = DokumentasiMagang::findOrFail($id);
        $dokumentasis = DokumentasiMagang::latest()->get();

        return view('admin.dokumentasi.index', compact('dokumentasis', 'editItem'));
    }

    /**
     * Perbarui dokumentasi. Foto opsional -- kalau admin tidak upload
     * file baru, foto lama tetap dipakai (tidak ditimpa null).
     */
    public function update(Request $request, $id)
    {
        $dokumentasi = DokumentasiMagang::findOrFail($id);

        $validated = $request->validate(
            $this->rules(fotoRequired: false),
            $this->messages()
        );

        if ($request->hasFile('foto')) {
        

            if ($dokumentasi->foto) {
                Storage::disk('public')->delete($dokumentasi->foto);
            }

            $validated['foto'] = $this->uploadFoto($request->file('foto'));
        } else {
            // Tidak ada file baru -- jangan sertakan 'foto' di update(),
            // supaya kolom foto yang sudah ada tidak ikut ditimpa/null.
            unset($validated['foto']);
        }

        $dokumentasi->update($validated);
        return redirect()
            ->route('admin.dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil diperbarui.');
    }

    /*Hapus dokumentasi beserta file fotonya.*/
    public function destroy($id)
    {
        $dokumentasi = DokumentasiMagang::findOrFail($id);

        if ($dokumentasi->foto) {
            Storage::disk('public')->delete($dokumentasi->foto);
        }

        $dokumentasi->delete();

        return redirect()
            ->route('admin.dokumentasi.index')
            ->with('success', 'Dokumentasi berhasil dihapus.');
    }

  private function uploadFoto(UploadedFile $file): string
{
    $filename = time() . '_' . $file->getClientOriginalName();

    Storage::disk('public')->putFileAs(
        'dokumentasi',
        $file,
        $filename
    );

    return "dokumentasi/{$filename}";
}
}