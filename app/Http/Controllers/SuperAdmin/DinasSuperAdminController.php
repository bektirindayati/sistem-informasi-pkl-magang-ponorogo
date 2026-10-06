<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Dinas\StoreDinasRequest;
use App\Http\Requests\Admin\Dinas\UpdateDinasRequest;
use App\Models\Dinas;
use Illuminate\Database\QueryException;

/** Kelola dinas (permission kelola-dinas, khusus super admin). */
class DinasSuperAdminController extends Controller
{
    public function index()
    {
        return view('admin.dinas.index', [
            'dinasList' => Dinas::latest()->paginate(10),
        ]);
    }

    public function store(StoreDinasRequest $request)
    {
        Dinas::create($request->validated());

        return redirect()->route('admin.dinas.index')->with('success', 'Dinas berhasil ditambahkan!');
    }

    public function edit($id)
    {
        return view('admin.dinas.edit', ['dinas' => Dinas::findOrFail($id)]);
    }

    public function update(UpdateDinasRequest $request, $id)
    {
        Dinas::findOrFail($id)->update($request->validated());

        return redirect()->route('admin.dinas.index')->with('success', 'Dinas berhasil diperbarui!');
    }

    public function destroy($id)
    {
        try {
            Dinas::findOrFail($id)->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'Dinas tidak bisa dihapus karena masih punya bidang atau pendaftaran yang terhubung. Nonaktifkan saja lewat status, atau hapus bidang/pendaftarannya dulu.');
        }

        return back()->with('success', 'Dinas berhasil dihapus!');
    }
}