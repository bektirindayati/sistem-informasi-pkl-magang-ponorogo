<x-mail::message>
# Halo, {{ $nama }}

{{ $judul }} Anda telah diterbitkan oleh dinas. Dokumen dapat diunduh kapan saja melalui halaman status pendaftaran Anda (salinan PDF juga dilampirkan pada email ini bila ukurannya memungkinkan).
@if ($jenis === 'sertifikat' && $kelompok)

Berkas ini memuat satu halaman sertifikat untuk setiap anggota kelompok. Mohon dibagikan kepada masing-masing anggota.
@endif

<x-mail::button :url="$url">
Buka Halaman Status
</x-mail::button>

Terima kasih atas partisipasi Anda dalam kegiatan magang ini.

Hormat kami,<br>
{{ config('app.name') }}
</x-mail::message>