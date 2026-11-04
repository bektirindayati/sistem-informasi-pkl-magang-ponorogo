<x-mail::message>
# Surat Balasan Anda Telah Terbit

Yth. **{{ $nama }}**,

Kabar baik untuk Anda: surat balasan penerimaan magang di **{{ $p->dinas_tujuan }}** telah resmi diterbitkan. Surat tersebut kami lampirkan pada email ini dalam format PDF, dan dapat pula diunduh kapan saja melalui halaman status pendaftaran Anda.

Mohon simpan surat ini dengan baik dan serahkan kepada pihak kampus atau sekolah sebagai bukti penerimaan magang Anda untuk periode **{{ $periode }}**. Mohon pula memperhatikan jadwal dan ketentuan yang berlaku selama kegiatan magang berlangsung.

<x-mail::button :url="$url">
Lihat Status Pendaftaran
</x-mail::button>

Apabila terdapat kekeliruan pada isi surat, segera hubungi dinas agar dapat kami perbaiki. Selamat mempersiapkan diri, dan semoga kegiatan magang Anda berjalan lancar.

Hormat kami,<br>
{{ config('app.name') }}
</x-mail::message>