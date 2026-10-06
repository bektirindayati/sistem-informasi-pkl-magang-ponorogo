<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PencarianRequest;
use App\Http\Requests\Admin\Pengguna\UpdatePenggunaRequest;
use App\Models\Dinas;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/** Kelola pengguna & role (permission kelola-admin, khusus super admin). */
class PenggunaSuperAdminController extends Controller
{
    public function index(PencarianRequest $request)
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

    public function update(UpdatePenggunaRequest $request, $id)
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