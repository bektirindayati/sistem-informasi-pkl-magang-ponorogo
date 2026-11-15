{{--
    Komponen: <x-admin.status-select :status="$item->status" :action="route('admin.verifikasi.update', $item->id)"
                  :alasan-penolakan="$item->alasan_penolakan" :alasan-revisi="$item->alasan_revisi" />

    Alasan penolakan/revisi diisi lewat modal (bukan inline di baris tabel), supaya
    tinggi baris selalu konsisten. Alasan disimpan di hidden input pada form baris ini.
--}}
@props(['status', 'action', 'alasanPenolakan' => null, 'alasanRevisi' => null, 'adaTerbit' => false])

@php
    $warna = [
        'pending'  => 'bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100 dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-500/25 dark:hover:bg-amber-500/20',
        'diterima' => 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/25 dark:hover:bg-emerald-500/20',
        'revisi'   => 'bg-orange-50 text-orange-800 border-orange-200 hover:bg-orange-100 dark:bg-orange-500/10 dark:text-orange-300 dark:border-orange-500/25 dark:hover:bg-orange-500/20',
        'ditolak'  => 'bg-rose-50 text-rose-800 border-rose-200 hover:bg-rose-100 dark:bg-rose-500/10 dark:text-rose-300 dark:border-rose-500/25 dark:hover:bg-rose-500/20',
        'selesai'  => 'bg-cyan-50 text-cyan-800 border-cyan-200 hover:bg-cyan-100 dark:bg-cyan-500/10 dark:text-cyan-300 dark:border-cyan-500/25 dark:hover:bg-cyan-500/20',
    ][$status] ?? 'bg-slate-50 text-slate-700 border-slate-200 dark:bg-slate-800 dark:text-slate-200 dark:border-slate-700';
@endphp

<form action="{{ $action }}" method="POST" class="js-status-form" data-current-status="{{ $status }}" data-ada-terbit="{{ $adaTerbit ? '1' : '0' }}">
    @csrf
    @method('PUT')

    <div class="relative w-full">
        <select name="status" onchange="handleStatusSelectChange(this)"
                class="js-status-dropdown appearance-none w-full h-9 pl-3 pr-9 rounded-lg border text-xs font-bold
                       cursor-pointer transition focus:outline-none focus:ring-2 focus:ring-cyan-400/40 {{ $warna }}">
            <option value="pending"  @selected($status === 'pending')>Menunggu</option>
            <option value="diterima" @selected($status === 'diterima')>Diterima</option>
            <option value="revisi"   @selected($status === 'revisi')>Perlu Revisi</option>
            <option value="ditolak"  @selected($status === 'ditolak')>Ditolak</option>
            @if (in_array($status, ['diterima', 'selesai'], true))
                <option value="selesai" @selected($status === 'selesai')>Magang Selesai</option>
            @endif
        </select>

        <svg class="pointer-events-none absolute right-2.5 top-1/2 h-4 w-4 -translate-y-1/2 opacity-60"
             xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="m6 8 4 4 4-4"/>
        </svg>
    </div>

    <input type="hidden" name="alasan_penolakan" class="js-alasan-penolakan-input" value="{{ $alasanPenolakan }}">
    <input type="hidden" name="alasan_revisi" class="js-alasan-revisi-input" value="{{ $alasanRevisi }}">
</form>

@once
{{-- Modal global (satu untuk semua baris) --}}
<div id="statusReasonModal" class="hidden fixed inset-0 z-[70] flex items-center justify-center px-4"
     role="dialog" aria-modal="true" aria-labelledby="statusReasonTitle">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="cancelStatusReason()"></div>

    <div class="relative w-full max-w-md rounded-2xl bg-white dark:bg-slate-900 border border-transparent dark:border-slate-800 shadow-xl p-6">
        <h3 id="statusReasonTitle" class="font-extrabold text-slate-900 dark:text-white text-base mb-1"></h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 mb-4">Alasan ini akan ditampilkan ke pendaftar.</p>

        <textarea id="statusReasonTextarea" rows="4" required
                  class="w-full text-sm px-3 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700
                         bg-white dark:bg-slate-950/60 text-slate-800 dark:text-slate-100
                         placeholder:text-slate-400 dark:placeholder:text-slate-500
                         focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-100 dark:focus:ring-cyan-500/20"></textarea>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" onclick="cancelStatusReason()"
                    class="h-9 px-4 text-xs font-bold rounded-lg transition
                           text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">Batal</button>
            <button type="button" onclick="confirmStatusReason()"
                    class="h-9 px-5 text-xs font-bold text-white rounded-lg transition
                           bg-slate-800 hover:bg-slate-900 dark:bg-blue-600 dark:hover:bg-blue-500">Simpan</button>
        </div>
    </div>
</div>

<script>
    let statusReasonActiveForm = null;
    let statusReasonActiveValue = null;

    function handleStatusSelectChange(select) {
        const form = select.closest('.js-status-form');
        const value = select.value;

        // Sama seperti halaman detail: beri peringatan bila ada surat/sertifikat yang sudah terbit.
        if (form.dataset.adaTerbit === '1' && value !== form.dataset.currentStatus) {
            const lanjut = confirm('Mengubah status dapat menarik surat atau sertifikat yang sudah diterbitkan dari pendaftar. Lanjutkan?');

            if (!lanjut) {
                select.value = form.dataset.currentStatus;
                return;
            }
        }

        if (value === 'ditolak' || value === 'revisi') {
            openStatusReasonModal(form, value);
            return;
        }

        // pending / diterima / selesai -> tidak perlu alasan, langsung submit
        form.submit();
    }

    function openStatusReasonModal(form, value) {
        statusReasonActiveForm = form;
        statusReasonActiveValue = value;

        const modal = document.getElementById('statusReasonModal');
        const title = document.getElementById('statusReasonTitle');
        const textarea = document.getElementById('statusReasonTextarea');
        const hiddenInput = form.querySelector(
            value === 'ditolak' ? '.js-alasan-penolakan-input' : '.js-alasan-revisi-input'
        );

        title.textContent = value === 'ditolak' ? 'Alasan Penolakan' : 'Catatan Revisi';
        textarea.placeholder = value === 'ditolak'
            ? 'Tulis alasan penolakan...'
            : 'Tulis catatan revisi yang perlu diperbaiki...';
        textarea.value = hiddenInput.value || '';

        modal.classList.remove('hidden');
        setTimeout(() => textarea.focus(), 50);
    }

    function confirmStatusReason() {
        const textarea = document.getElementById('statusReasonTextarea');

        if (!textarea.value.trim()) {
            textarea.focus();
            return;
        }

        const hiddenInput = statusReasonActiveForm.querySelector(
            statusReasonActiveValue === 'ditolak' ? '.js-alasan-penolakan-input' : '.js-alasan-revisi-input'
        );

        hiddenInput.value = textarea.value;
        document.getElementById('statusReasonModal').classList.add('hidden');

        statusReasonActiveForm.submit();
    }

    function cancelStatusReason() {
        document.getElementById('statusReasonModal').classList.add('hidden');

        if (statusReasonActiveForm) {
            const select = statusReasonActiveForm.querySelector('.js-status-dropdown');
            select.value = statusReasonActiveForm.dataset.currentStatus;
        }

        statusReasonActiveForm = null;
        statusReasonActiveValue = null;
    }

    // Esc menutup modal (sama seperti Batal)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !document.getElementById('statusReasonModal').classList.contains('hidden')) {
            cancelStatusReason();
        }
    });
</script>
@endonce