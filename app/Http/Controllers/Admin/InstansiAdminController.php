<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Instansi\DestroyInstansiRequest;
use App\Http\Requests\Admin\Instansi\StoreInstansiRequest;
use App\Models\Dinas;
use App\Models\InstansiBidang;
use Illuminate\Support\Facades\Auth;

/** Kelola bidang instansi: admin dinas hanya melihat/mengubah bidang dinasnya sendiri. */
class InstansiAdminController extends Controller
{
    public function index()
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

    public function store(StoreInstansiRequest $request)
    {
        InstansiBidang::create($request->dataBidang());

        return back()->with('success', 'Bidang / Instansi berhasil ditambahkan!');
    }

    public function destroy(DestroyInstansiRequest $request)
    {
        $request->bidang()->delete();

        return back()->with('success', 'Bidang / Instansi berhasil dihapus!');
    }
}