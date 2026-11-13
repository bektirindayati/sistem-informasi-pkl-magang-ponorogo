<x-mail::message>
# Selamat, {{ $nama }}!

Dengan gembira kami sampaikan bahwa pendaftaran magang Anda di **{{ $p->dinas_tujuan }}** pada bidang **{{ $p->divisi }}** telah **diterima**. Seluruh berkas yang Anda ajukan telah kami periksa dan dinyatakan memenuhi persyaratan, sehingga Anda dapat mulai mempersiapkan diri untuk mengikuti kegiatan magang sesuai jadwal yang telah Anda ajukan.

<x-mail::panel>
**Instansi tujuan:** {{ $p->dinas_tujuan }}<br>
**Bidang penempatan:** {{ $p->divisi }}<br>
**Periode magang:** {{ $periode }}@if ($p->tipe === 'kelompok')<br>
**Peserta:** Kelompok, {{ $p->anggotas->count() + 1 }} orang (Anda sebagai ketua)@endif
</x-mail::panel>

Surat balasan penerimaan magang, surat keterangan selesai magang, dan sertifikat akan diterbitkan melalui sistem, bukan melalui email. **Email ini adalah satu-satunya pemberitahuan yang kami kirim lewat surat elektronik**, sehingga mohon cek halaman status pendaftaran Anda secara berkala. Tombol unduh dokumen akan muncul di halaman tersebut begitu dokumen diterbitkan.
@if ($p->tipe === 'kelompok')

Sebagai ketua kelompok, Anda dapat mengunduh dokumen tersebut dan membagikannya kepada anggota kelompok.
@endif

<x-mail::button :url="$url">
Buka Halaman Status
</x-mail::button>

Apabila ada hal yang ingin ditanyakan, silakan menghubungi dinas melalui kontak resmi yang tersedia. Terima kasih atas minat dan kepercayaan Anda kepada kami.

Hormat kami,<br>
{{ config('app.name') }}
</x-mail::message>