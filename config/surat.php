<?php

return [
    // Ditambahkan di belakang nama dinas: "Dinas ... Kabupaten Ponorogo".
    'wilayah' => 'Kabupaten Ponorogo',

    'template' => [
    'magang'           => resource_path('templates/surat_balasan_magang.docx'),
    'magang_kelompok'  => resource_path('templates/surat_balasan_magang_kelompok.docx'),
    'selesai'          => resource_path('templates/surat_selesai_magang.docx'),
    'selesai_kelompok' => resource_path('templates/surat_selesai_magang_kelompok.docx'),
    'sertifikat'       => resource_path('templates/sertifikat_magang.docx'),
    // 'pkl' => ..., 'pkl_kelompok' => ...   (bila memakai template PKL terpisah)
],

    'zona_waktu' => 'Asia/Jakarta',
];