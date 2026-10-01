<?php

namespace App\Http\Requests;

/**
 * Ubah data dinas. Saat ini aturannya sama dengan StoreDinasRequest;
 * dipisah supaya kalau nanti ada aturan khusus update (mis. unique
 * nama_dinas dengan ignore id) cukup diubah di file ini.
 */
class UpdateDinasRequest extends StoreDinasRequest
{
}