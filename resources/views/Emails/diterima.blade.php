<x-mail::message>
# Selamat, {{ $nama }}!

Dengan gembira kami sampaikan bahwa pendaftaran magang Anda di **{{ $p->dinas_tujuan }}** pada bidang **{{ $p->divisi }}** telah **diterima**. Seluruh berkas yang Anda ajukan telah kami periksa dan dinyatakan memenuhi persyaratan, sehingga Anda dapat mulai mempersiapkan diri untuk mengikuti kegiatan magang sesuai jadwal yang telah Anda ajukan.

<x-mail::panel>
**Instansi tujuan:** {{ $p->dinas_tujuan }}<br>
**Bidang penempatan:** {{ $p->divisi }}<br>
**Periode magang:** {{ $periode }}
</x-mail::panel>

Saat ini surat balasan penerimaan magang sedang dalam proses penyusunan dan penandatanganan oleh pimpinan dinas. Begitu surat tersebut diterbitkan, kami akan segera mengirimkannya ke alamat email ini sekaligus menampilkannya pada halaman status pendaftaran Anda, sehingga dapat diunduh dan diserahkan kepada pihak kampus atau sekolah Anda. Anda dapat memantau perkembangannya kapan saja melalui dashboard berikut.

<x-mail::button :url="$url">
Buka Dashboard
</x-mail::button>

Apabila ada hal yang ingin ditanyakan, silakan menghubungi dinas melalui kontak resmi yang tersedia. Terima kasih atas minat dan kepercayaan Anda kepada kami.

Hormat kami,<br>
{{ config('app.name') }}
</x-mail::message>