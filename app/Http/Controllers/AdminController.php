<?php

namespace App\Http\Controllers;

use App\Http\Requests\CetakSuratBalasanRequest;
use App\Http\Requests\DestroyInstansiRequest;
use App\Http\Requests\PencarianRequest;
use App\Http\Requests\ShowPendaftarRequest;
use App\Http\Requests\StoreDinasRequest;
use App\Http\Requests\StoreInstansiRequest;
use App\Http\Requests\UpdateDinasRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\Dinas;
use App\Models\InstansiBidang;
use App\Models\PendaftaranMagang;
use App\Models\User;
use App\Services\SuratBalasanGenerator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // ==================== DASHBOARD ====================

    public function dashboard()
    {
        $dasar = PendaftaranMagang::untukAdmin(Auth::user())->sudahDikirim();

        $perStatus = (clone $dasar)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalMagang  = $perStatus->sum();
        $totalPkl     = $perStatus['diterima'] ?? 0;
        $totalPending = ($perStatus['pending'] ?? 0) + ($perStatus['revisi'] ?? 0);

        $pendaftarTerbaru = (clone $dasar)->latest()->take(5)->get();

        return view('admin.dashboardadmin', compact(
            'totalMagang',
            'totalPkl',
            'totalPending',
            'pendaftarTerbaru'
        ));
    }

    // ==================== VERIFIKASI BERKAS ====================

    public function verifikasiBerkas(PencarianRequest $request)
    {
        $pendaftars = PendaftaranMagang::latest()
            ->untukAdmin(Auth::user())
            ->sudahDikirim()
            ->cariNama($request->kata())
            ->paginate(10)
            ->withQueryString();

        return view('admin.verifikasi.index', compact('pendaftars'));
    }

    public function updateStatus(UpdateStatusRequest $request)
    {
        $request->pendaftaran()->update($request->dataStatus());

        return back()->with('success', 'Status verifikasi berkas berhasil diperbarui!');
    }

    public function cetakSuratBalasan(CetakSuratBalasanRequest $request, SuratBalasanGenerator $generator)
    {
        $path = $generator->buat($request->pendaftaran());

        if (! $path) {
            return back()->with('error', 'File template surat tidak ditemukan di folder storage/templates/');
        }

        return response()->download($path)->deleteFileAfterSend(true);
    }

    // ==================== DATA PENDAFTAR ====================

    public function showPendaftar(ShowPendaftarRequest $request)
    {
        return view('admin.pendaftar.show', [
            'pendaftaran' => $request->pendaftaran(),
        ]);
    }

    public function indexPendaftar(PencarianRequest $request)
    {
        $admin  = Auth::user();
        $search = $request->kata();

        $dasar = fn () => PendaftaranMagang::latest()->untukAdmin($admin)->cariNama($search);

        $pemagangAktif   = $dasar()->aktif()->get();
        $pemagangSelesai = $dasar()->selesai()->get();
        $pemagangDitolak = $dasar()->ditolak()->get();

        return view('admin.pendaftar.index', compact(
            'pemagangAktif',
            'pemagangSelesai',
            'pemagangDitolak',
            'search'
        ));
    }

    // ==================== KELOLA INSTANSI / BIDANG ====================

    public function kelolaInstansi()
    {
        $admin = Auth::user();

        $bidangs = InstansiBidang::with('dinas')
            ->latest()
            ->when(! $admin->isSuperAdmin(), fn ($q) => $q->where('dinas_id', $admin->dinas_id))
            ->paginate(10);

        $dinasList = $admin->isSuperAdmin()
            ? Dinas::orderBy('nama_dinas')->get()
            : collect();

        return view('admin.instansi.index', compact('bidangs', 'dinasList'));
    }

    public function storeInstansi(StoreInstansiRequest $request)
    {
        InstansiBidang::create($request->dataBidang());

        return back()->with('success', 'Bidang / Instansi berhasil ditambahkan!');
    }

    public function destroyInstansi(DestroyInstansiRequest $request)
    {
        $request->bidang()->delete();

        return back()->with('success', 'Bidang / Instansi berhasil dihapus!');
    }

    // ==================== KELOLA DINAS ====================

    public function kelolaDinas()
    {
        $dinasList = Dinas::latest()->paginate(10);

        return view('admin.dinas.index', compact('dinasList'));
    }

    public function storeDinas(StoreDinasRequest $request)
    {
        Dinas::create($request->validated());

        return redirect()->route('admin.dinas.index')->with('success', 'Dinas berhasil ditambahkan!');
    }

    public function editDinas($id)
    {
        return view('admin.dinas.edit', ['dinas' => Dinas::findOrFail($id)]);
    }

    public function updateDinas(UpdateDinasRequest $request, $id)
    {
        Dinas::findOrFail($id)->update($request->validated());

        return redirect()->route('admin.dinas.index')->with('success', 'Dinas berhasil diperbarui!');
    }

    public function destroyDinas($id)
    {
        try {
            Dinas::findOrFail($id)->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Dinas tidak bisa dihapus karena masih punya bidang atau pendaftaran yang terhubung. Nonaktifkan saja lewat status, atau hapus bidang/pendaftarannya dulu.');
        }

        return back()->with('success', 'Dinas berhasil dihapus!');
    }

    // ==================== KELOLA PENGGUNA (super admin) ====================

    public function kelolaPengguna(PencarianRequest $request)
    {
        $kata = $request->kata();

        $users = User::with('dinas')
            ->latest()
            ->when($kata, fn ($q) => $q->where(function ($q) use ($kata) {
                $q->where('name', 'like', "%{$kata}%")
                  ->orWhere('email', 'like', "%{$kata}%");
            }))
            ->paginate(15)
            ->withQueryString();

        $dinasList = Dinas::orderBy('nama_dinas')->get();

        return view('admin.pengguna.index', compact('users', 'dinasList'));
    }

    public function updatePengguna(UpdatePenggunaRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->dataPengguna();

        DB::transaction(function () use ($user, $data) {
            $user->update($data);
            $user->syncRoles([$data['role']]); // sinkron ke Spatie
        });

        return back()->with('success', "Role {$user->name} berhasil diperbarui.");
    }
}