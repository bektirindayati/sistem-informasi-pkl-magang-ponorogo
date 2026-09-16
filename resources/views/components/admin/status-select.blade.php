{{--
    Komponen: <x-admin.status-select :status="$item->status" :action="route('admin.verifikasi.update', $item->id)"
                  :alasan-penolakan="$item->alasan_penolakan" :alasan-revisi="$item->alasan_revisi" />

    PERBAIKAN (poin 3 saran mentor): sebelumnya textarea alasan + tombol
    Simpan muncul LANGSUNG DI DALAM baris tabel saat status "Ditolak"/
    "Revisi" dipilih, membuat tinggi baris itu beda sendiri dari baris
    lain (lihat screenshot: baris "Sandrina" jadi jauh lebih tinggi).
    Sekarang alasan diisi lewat MODAL (muncul di tengah layar), jadi
    tinggi baris tabel selalu konsisten apapun statusnya. Alasan tetap
    disimpan sebagai hidden input di dalam form baris ini, cuma diisi
    lewat modal sebelum form itu di-submit.
--}}
@props(['status', 'action', 'alasanPenolakan' => null, 'alasanRevisi' => null])

<form action="{{ $action }}" method="POST" class="js-status-form" data-current-status="{{ $status }}">
    @csrf
    @method('PUT')

    <div class="relative inline-block w-full">
        <select name="status" onchange="handleStatusSelectChange(this)" class="js-status-dropdown appearance-none w-full px-4 py-2 pr-10 rounded-xl border font-bold text-xs focus:ring-2 focus:ring-blue-500 outline-none cursor-pointer transition shadow-sm
            {{ $status == 'pending' ? 'bg-amber-50 text-amber-800 border-amber-200 hover:bg-amber-100' : '' }}
            {{ $status == 'diterima' ? 'bg-emerald-50 text-emerald-800 border-emerald-200 hover:bg-emerald-100' : '' }}
            {{ $status == 'revisi' ? 'bg-orange-50 text-orange-800 border-orange-200 hover:bg-orange-100' : '' }}
            {{ $status == 'ditolak' ? 'bg-rose-50 text-rose-800 border-rose-200 hover:bg-rose-100' : '' }}">
            <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>⏳ Pending</option>
            <option value="diterima" {{ $status == 'diterima' ? 'selected' : '' }}>✅ Diterima</option>
            <option value="revisi" {{ $status == 'revisi' ? 'selected' : '' }}>✏️ Revisi</option>
            <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>❌ Ditolak</option>
        </select>
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500 text-xs">▼</div>
    </div>

    {{-- Alasan tidak lagi tampil inline di baris tabel -- diisi lewat modal --}}
    <input type="hidden" name="alasan_penolakan" class="js-alasan-penolakan-input" value="{{ $alasanPenolakan }}">
    <input type="hidden" name="alasan_revisi" class="js-alasan-revisi-input" value="{{ $alasanRevisi }}">
</form>

@once
{{-- Modal global (satu untuk semua baris, dirender sekali lewat @once) --}}
<div id="statusReasonModal" class="hidden fixed inset-0 z-[70] flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="cancelStatusReason()"></div>

    <div class="relative bg-white w-full max-w-md rounded-2xl shadow-xl p-6">
        <h3 id="statusReasonTitle" class="font-extrabold text-slate-900 text-base mb-1"></h3>
        <p class="text-xs text-slate-500 mb-4">Alasan ini akan ditampilkan ke pendaftar.</p>

        <textarea id="statusReasonTextarea" rows="4" required
            class="w-full text-sm px-3 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-400 outline-none"></textarea>

        <div class="flex justify-end gap-2 mt-4">
            <button type="button" onclick="cancelStatusReason()" class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-800 rounded-xl transition">Batal</button>
            <button type="button" onclick="confirmStatusReason()" class="px-5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition">Simpan</button>
        </div>
    </div>
</div>

<script>
    let statusReasonActiveForm = null;
    let statusReasonActiveValue = null;

    function handleStatusSelectChange(select) {
        const form = select.closest('.js-status-form');
        const value = select.value;

        if (value === 'ditolak' || value === 'revisi') {
            openStatusReasonModal(form, value);
            return;
        }

        // pending / diterima -> tidak perlu alasan, langsung submit
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
</script>
@endonce