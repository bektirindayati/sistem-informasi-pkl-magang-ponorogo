<?php

return [

    /* File template DOCX (placeholder ${...}). Bisa diganti tanpa mengubah kode. */
    'template' => env('SURAT_TEMPLATE', storage_path('app/templates/surat_template.docx')),

    /*
     | Program LibreOffice untuk mengubah DOCX menjadi PDF.
     | Linux  : soffice  (apt install libreoffice-writer fonts-liberation)
     | Windows: C:\Program Files\LibreOffice\program\soffice.exe
     */
    'soffice'     => env('SOFFICE_PATH', 'soffice'),
    'batas_waktu' => 90, // detik

    /* Tempat penyimpanan PDF (disk privat, tidak bisa dibuka lewat URL langsung). */
    'disk'   => env('SURAT_DISK', 'local'),
    'folder' => 'surat',

    'zona_waktu' => 'Asia/Jakarta',

    /*
     | true  : surat baru bisa diterbitkan setelah admin mengunggah PDF yang
     |         SUDAH ditandatangani (mis. TTE BSrE) -- direkomendasikan.
     | false : admin boleh menerbitkan PDF buatan sistem (ruang tanda tangan kosong).
     */
    'wajib_unggah_ttd' => (bool) env('SURAT_WAJIB_UNGGAH_TTD', true),
];