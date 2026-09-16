<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PendaftaranMagang;
use App\Models\Dinas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpWord\TemplateProcessor;
use App\Http\Requests\UpdateStatusRequest;
use App\Http\Requests\StoreInstansiRequest;
use App\Http\Requests\StoreDinasRequest;

class AdminController extends Controller
{
    private function applyAdminFilter($query)
    {
        $admin = Auth::user();

        if (!$admin->isSuperAdmin()) {
            $query->where('dinas_id', $admin->dinas_id);
        }

        return $query;
    }

    /**
     * Dashboard Admin.
     */
   public function dashboard(Request $request)
{
    try {
        $baseQuery = PendaftaranMagang::query();

        $this->applyAdminFilter($baseQuery);

        // Draft belum dianggap sebagai pengajuan.
        // Hanya pengajuan yang sudah dikirim yang ditampilkan di admin.
        $baseQuery->whereIn('status', [
            'pending',
            'revisi',
            'diterima',
            'ditolak',
        ]);

        // Total seluruh pengajuan yang sudah masuk ke sistem admin
        $totalMagang = (clone $baseQuery)->count();

        // Total pendaftar yang sudah diterima
        $totalPkl = (clone $baseQuery)
            ->where('status', 'diterima')
            ->count();

        // Total yang masih perlu ditindaklanjuti admin
        $totalPending = (clone $baseQuery)
            ->whereIn('status', ['pending', 'revisi'])
            ->count();

        // Pengajuan terbaru yang sudah masuk ke admin
        $pendaftarTerbaru = (clone $baseQuery)
            ->latest()
            ->take(5)
            ->get();

    } catch (\Exception $e) {
        $totalMagang = 0;
        $totalPkl = 0;
        $totalPending = 0;
        $pendaftarTerbaru = collect();
    }

    return view('admin.dashboardadmin', compact(
        'totalMagang',
        'totalPkl',
        'totalPending',
        'pendaftarTerbaru'
    ));
}


/**
 * Menampilkan halaman Verifikasi Berkas.
 * Bisa dicari berdasarkan nama pendaftar via ?search=
 */
public function verifikasiBerkas(Request $request)
{
    $query = PendaftaranMagang::latest();

    $this->applyAdminFilter($query);

    // Draft adalah data pribadi user dan belum menjadi pengajuan.
    // Admin hanya melihat pendaftaran yang sudah dikirim.
    $query->whereIn('status', [
        'pending',
        'revisi',
        'diterima',
        'ditolak',
    ]);

    if ($request->filled('search')) {
        $query->where(
            'nama_lengkap',
            'like',
            '%' . $request->search . '%'
        );
    }

    $pendaftars = $query
        ->paginate(10)
        ->withQueryString();

    return view('admin.verifikasi.index', compact('pendaftars'));
}
    /**
     * Memperbarui status verifikasi berkas (Terima/Tolak/Revisi/Pending).
     * BARU: admin dinas cuma boleh ubah status pendaftar milik dinasnya.
     */
    public function updateStatus(UpdateStatusRequest $request, $id)
    {
        $admin = Auth::user();
        $pendaftaran = PendaftaranMagang::findOrFail($id);

        abort_if(
            !$admin->isSuperAdmin() && $pendaftaran->dinas_id !== $admin->dinas_id,
            403,
            'Anda tidak memiliki akses untuk mengubah status pendaftar dinas lain.'
        );

        // FIX: sebelumnya cuma 'status' yang disimpan, padahal
        // UpdateStatusRequest sudah memvalidasi alasan_penolakan &
        // alasan_revisi juga — keduanya diam-diam tidak pernah tersimpan.
        // validated() otomatis berisi null untuk keduanya kalau tidak
        // dikirim, jadi alasan lama ikut terhapus saat status berubah
        // ke selain ditolak/revisi (efek samping yang memang diinginkan).
        $pendaftaran->update($request->validated());

        return back()->with('success', 'Status verifikasi berkas berhasil diperbarui!');
    }

    /**
     * Fitur untuk mencetak dan mengunduh surat balasan magang menggunakan PHPWord.
     * BARU: admin dinas cuma boleh cetak surat untuk pendaftar dinasnya.
     */
    public function cetakSuratBalasan($id)
    {
        $admin = Auth::user();
        $pendaftaran = PendaftaranMagang::findOrFail($id);

        abort_if(
            !$admin->isSuperAdmin() && $pendaftaran->dinas_id !== $admin->dinas_id,
            403,
            'Anda tidak memiliki akses untuk mencetak surat pendaftar dinas lain.'
        );

        $templatePath = storage_path('app/templates/surat_template.docx');

        if (!file_exists($templatePath)) {
            return back()->with('error', 'File template surat tidak ditemukan di folder storage/templates/');
        }

        $templateProcessor = new TemplateProcessor($templatePath);

        $templateProcessor->setValue('tanggal', date('d F Y'));
        $templateProcessor->setValue('universitas', $pendaftaran->universitas ?? $pendaftaran->instansi ?? 'Universitas Negeri Surabaya');
        $templateProcessor->setValue('tanggal_surat_kampus', '20 Mei 2026');
        $templateProcessor->setValue('nomor_surat_kampus', '123/UNB/DT/2026');
        $templateProcessor->setValue('nama', $pendaftaran->nama_lengkap ?? 'Nama Peserta');
        $templateProcessor->setValue('nim', $pendaftaran->nim_nisn ?? 'NIM/NISN');
        $templateProcessor->setValue('prodi', $pendaftaran->jurusan ?? 'Program Studi');
        $templateProcessor->setValue('tanggal_mulai', $pendaftaran->tanggal_mulai . ' s.d. ' . $pendaftaran->tanggal_selesai);

        $fileName = 'Surat_Keterangan_Magang_' . ($pendaftaran->nim_nisn ?? $id) . '.docx';
        $pathToSave = storage_path('app/public/' . $fileName);

        if (!file_exists(storage_path('app/public'))) {
            mkdir(storage_path('app/public'), 0777, true);
        }

        $templateProcessor->saveAs($pathToSave);

        return response()->download($pathToSave)->deleteFileAfterSend(true);
    }

    /**
     * Menampilkan halaman Kelola Instansi/Bidang.
     * BARU: admin dinas cuma lihat bidang milik dinasnya sendiri.
     */
    public function kelolaInstansi()
    {
        $admin = Auth::user();

        $query = \App\Models\InstansiBidang::with('dinas')->latest();

        if (!$admin->isSuperAdmin()) {
            $query->where('dinas_id', $admin->dinas_id);
        }

        $bidangs = $query->paginate(10);

        $dinasList = $admin->isSuperAdmin()
            ? Dinas::orderBy('nama_dinas')->get()
            : collect();

        return view('admin.instansi.index', compact('bidangs', 'dinasList'));
    }

    /**
     * Menyimpan data bidang baru.
     * BARU: admin dinas dipaksa pakai dinas_id miliknya sendiri (bukan
     * dari request) — mencegah admin dinas menambah bidang untuk dinas lain.
     */
    public function storeInstansi(StoreInstansiRequest $request)
    {
        $admin = Auth::user();

        \App\Models\InstansiBidang::create([
            'dinas_id' => $admin->isSuperAdmin() ? $request->validated('dinas_id') : $admin->dinas_id,
            'nama_bidang' => $request->validated('nama_bidang'),
            'deskripsi' => $request->validated('deskripsi'),
        ]);

        return back()->with('success', 'Bidang / Instansi berhasil ditambahkan!');
    }

    /**
     * Menghapus data bidang.
     * BARU: admin dinas cuma boleh hapus bidang milik dinasnya sendiri.
     */
    public function destroyInstansi($id)
    {
        $admin = Auth::user();
        $bidang = \App\Models\InstansiBidang::findOrFail($id);

        abort_if(
            !$admin->isSuperAdmin() && $bidang->dinas_id !== $admin->dinas_id,
            403,
            'Anda tidak memiliki akses untuk menghapus bidang milik dinas lain.'
        );

        $bidang->delete();

        return back()->with('success', 'Bidang / Instansi berhasil dihapus!');
    }

    /**
     * Menampilkan detail lengkap data pendaftar.
     * BARU: admin dinas cuma boleh lihat pendaftar milik dinasnya sendiri.
     */
    public function showPendaftar($id)
    {
        $admin = Auth::user();
        $pendaftaran = PendaftaranMagang::findOrFail($id);

        abort_if(
            !$admin->isSuperAdmin() && $pendaftaran->dinas_id !== $admin->dinas_id,
            403,
            'Anda tidak memiliki akses ke pendaftaran dinas lain.'
        );

        return view('admin.pendaftar.show', compact('pendaftaran'));
    }
    public function indexPendaftar(Request $request)
    {
        $today = now()->toDateString();
        $search = $request->input('search');

        // 1. Pemagang Aktif / Proses: pending, ATAU diterima tapi belum lewat tanggal_selesai
        $queryAktif = PendaftaranMagang::latest();
        $this->applyAdminFilter($queryAktif);
        $queryAktif->where(function ($q) use ($today) {
            $q->where('status', 'pending')
              ->orWhere(function ($q2) use ($today) {
                  $q2->where('status', 'diterima')
                     ->where(function ($q3) use ($today) {
                         $q3->whereNull('tanggal_selesai')
                            ->orWhereDate('tanggal_selesai', '>=', $today);
                     });
              });
        });
        if ($search) {
            $queryAktif->where('nama_lengkap', 'like', "%{$search}%");
        }
        $pemagangAktif = $queryAktif->get();

        // 2. Selesai: diterima yang tanggal_selesai-nya sudah lewat
        $querySelesai = PendaftaranMagang::latest();
        $this->applyAdminFilter($querySelesai);
        $querySelesai->where('status', 'diterima')
            ->whereDate('tanggal_selesai', '<', $today);
        if ($search) {
            $querySelesai->where('nama_lengkap', 'like', "%{$search}%");
        }
        $pemagangSelesai = $querySelesai->get();

        // 3. Ditolak: query sendiri, tidak lagi digabung dengan Selesai
        $queryDitolak = PendaftaranMagang::latest();
        $this->applyAdminFilter($queryDitolak);
        $queryDitolak->where('status', 'ditolak');
        if ($search) {
            $queryDitolak->where('nama_lengkap', 'like', "%{$search}%");
        }
        $pemagangDitolak = $queryDitolak->get();

        return view('admin.pendaftar.index', compact(
            'pemagangAktif',
            'pemagangSelesai',
            'pemagangDitolak',
            'search'
        ));
    }
    // ==================== KELOLA DINAS ====================

    /**
     * Menampilkan halaman Kelola Dinas.
     */
    public function kelolaDinas()
    {
        $dinasList = Dinas::latest()->paginate(10);

        return view('admin.dinas.index', compact('dinasList'));
    }

    /**
     * Menyimpan dinas baru.
     */
    public function storeDinas(StoreDinasRequest $request)
    {
        Dinas::create($request->validated());

        return redirect()->route('admin.dinas.index')->with('success', 'Dinas berhasil ditambahkan!');
    }

    /**
     * Menampilkan form edit dinas.
     */
    public function editDinas($id)
    {
        $dinas = Dinas::findOrFail($id);

        return view('admin.dinas.edit', compact('dinas'));
    }

    /**
     * Menyimpan perubahan data dinas.
     */
    public function updateDinas(StoreDinasRequest $request, $id)
    {
        $dinas = Dinas::findOrFail($id);
        $dinas->update($request->validated());

        return redirect()->route('admin.dinas.index')->with('success', 'Dinas berhasil diperbarui!');
    }

    /**
     * Menghapus dinas.
     * CATATAN: kalau dinas ini masih punya bidang (instansi_bidangs) atau
     * pendaftaran (pendaftaran_magangs) yang terhubung, penghapusan bisa
     */
    public function destroyDinas($id)
    {
        $dinas = Dinas::findOrFail($id);

        try {
            $dinas->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return back()->with('error', 'Dinas tidak bisa dihapus karena masih punya bidang atau pendaftaran yang terhubung. Nonaktifkan saja lewat status, atau hapus bidang/pendaftarannya dulu.');
        }

        return back()->with('success', 'Dinas berhasil dihapus!');
    }

    // KELOLA ADMIN (khusus super admin) 

    public function kelolaPengguna(Request $request)
    {
        $query = User::with('dinas')->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->paginate(15)->withQueryString();
        $dinasList = Dinas::orderBy('nama_dinas')->get();

        return view('admin.pengguna.index', compact('users', 'dinasList'));
    }

   
  public function updatePengguna(Request $request, $id)
{
    $user = User::findOrFail($id);

    $validated = $request->validate([
        'role' => ['required', 'in:user,admin,super_admin'],
        'dinas_id' => ['nullable', 'exists:dinases,id'],
    ]);

    // Admin dinas WAJIB punya dinas_id. Kalau bukan role 'admin'
    // (user biasa atau super_admin), dinas_id dikosongkan — super
    // admin tidak terikat 1 dinas, dan user biasa tidak butuh dinas.
    if ($validated['role'] !== 'admin') {
        $validated['dinas_id'] = null;
    } elseif (empty($validated['dinas_id'])) {
        return back()->withErrors([
            'dinas_id' => 'Admin dinas wajib ditugaskan ke salah satu dinas.'
        ]);
    }

    $user->update($validated);

    // Sinkronkan role ke Spatie
    $user->syncRoles([$validated['role']]);

    return back()->with('success', "Role {$user->name} berhasil diperbarui.");
}}