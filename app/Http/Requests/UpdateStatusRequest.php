<?php

namespace App\Http\Requests;

/** Ubah status verifikasi (pending/revisi/diterima/ditolak). */
class UpdateStatusRequest extends AdminPendaftaranRequest
{
    protected function pesanAksesDitolak(): string
    {
        return 'Anda tidak memiliki akses untuk mengubah status pendaftar dinas lain.';
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:pending,revisi,diterima,ditolak'],
            'alasan_penolakan' => ['nullable', 'required_if:status,ditolak', 'string'],
            'alasan_revisi' => ['nullable', 'required_if:status,revisi', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_penolakan.required_if' => 'Alasan penolakan wajib diisi kalau status diubah menjadi Ditolak.',
            'alasan_revisi.required_if' => 'Alasan revisi wajib diisi kalau status diubah menjadi Revisi.',
        ];
    }

    /** Alasan yang tidak sesuai status baru otomatis dikosongkan. */
    public function dataStatus(): array
    {
        $data = $this->validated();

        return [
            'status' => $data['status'],
            'alasan_penolakan' => $data['status'] === 'ditolak' ? ($data['alasan_penolakan'] ?? null) : null,
            'alasan_revisi' => $data['status'] === 'revisi' ? ($data['alasan_revisi'] ?? null) : null,
        ];
    }
}