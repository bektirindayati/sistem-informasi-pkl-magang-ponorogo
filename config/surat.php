<?php

return [
    // Ditambahkan di belakang nama dinas: "Dinas ... Kabupaten Ponorogo".
    'wilayah' => 'Kabupaten Ponorogo',

    // Template Word. Template PKL (siswa) belum ada: isi path-nya di sini begitu sudah tersedia.
    'template' => [
    'magang'     => resource_path('templates/surat_balasan_magang.docx'),
    'pkl'        => null,
    'sertifikat' => resource_path('templates/sertifikat_magang.docx'),
],

    'zona_waktu' => 'Asia/Jakarta',
];