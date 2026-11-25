<?php

/** kelompok => [kode => [arti, contoh isi]] */
return [
    'Kop dan pejabat penandatangan (dari profil dinas)' => [
        'kop_nama_dinas' => ['Nama dinas, huruf besar', 'DINAS KOMUNIKASI, INFORMASI DAN STATISTIK'],
        'kop_alamat'     => ['Alamat dinas (satu baris)', 'Jl. Ir. Juanda Nomor 198, Ponorogo'],
        'kop_kontak'     => ['Telepon dan faksimile', 'Telepon 0352-3592999'],
        'kop_web_email'  => ['Laman dan pos-el', 'Laman kominfo.ponorogo.id, Pos-el kominfo@ponorogo.go.id'],
        'jabatan_pengirim' => ['Jabatan pejabat penandatangan', 'Kepala Dinas'],
        'nama_pengirim'  => ['Nama pejabat', 'Drs. Contoh, M.M.'],
        'pangkat'        => ['Pangkat pejabat', 'Pembina Utama Muda'],
        'gol'            => ['Golongan pejabat', 'IV/c'],
        'nip_pengirim'   => ['NIP pejabat', '19700101 199501 1 001'],
        'nama_dinas'     => ['Nama dinas lengkap dengan wilayah', 'Dinas Komunikasi, Informasi dan Statistik Kabupaten Ponorogo'],
    ],
    'Rujukan surat pengantar (dari formulir pendaftaran)' => [
        'nama_penerima'       => ['Tujuan surat: jabatan pengirim lalu nama kampus/sekolah', 'Direktur Pendidikan / Universitas Contoh'],
        'jabatan_penerima'    => ['Jabatan pejabat kampus/sekolah', 'Direktur Pendidikan'],
        'pengirim_surat'      => ['Jabatan dan nama kampus/sekolah', 'Direktur Pendidikan Universitas Contoh'],
        'kota_tujuan'         => ['Kota kampus/sekolah, huruf besar', 'SURABAYA'],
        'tgl_bln_thn'         => ['Tanggal surat pengantar', '2 November 2026'],
        'nomor_suratpengirim' => ['Nomor surat pengantar ketua', 'B/123/UN.01/2026'],
        'tanggal_surat'       => ['Tanggal hari ini', '25 November 2026'],
    ],
    'Data peserta (ketua atau pendaftar mandiri) dan periode' => [
        'nama_lengkap'    => ['Nama lengkap', 'Contoh Nama Ketua'],
        'nim_nisn'        => ['NIM / NISN', '24050970001'],
        'jurusan'         => ['Jurusan / program studi', 'S1 Teknik Informatika'],
        'instansi'        => ['Asal kampus / sekolah', 'Universitas Contoh'],
        'tanggal_mulai'   => ['Tanggal mulai magang', '9 Desember 2026'],
        'tanggal_selesai' => ['Tanggal selesai magang', '9 Maret 2027'],
        'bidang'          => ['Bidang penempatan (khusus sertifikat)', 'Informasi dan Komunikasi Publik'],
        'periode'         => ['Rentang periode magang (khusus sertifikat)', '09 Desember 2026 - 09 Maret 2027'],
    ],
    'Tabel peserta kelompok (satu baris contoh di dalam tabel)' => [
        'no'               => ['Nomor urut', '1'],
        'nama_anggota'     => ['Nama peserta', 'Contoh Anggota'],
        'nim_anggota'      => ['NIM / NISN peserta', '24050970002'],
        'jurusan_anggota'  => ['Jurusan peserta', 'S1 Teknik Informatika'],
        'instansi_anggota' => ['Asal kampus peserta', 'Universitas Contoh'],
    ],
    'Diisi manual oleh dinas (dibiarkan apa adanya oleh sistem)' => [
        'nomor_naskah'  => ['Nomor naskah dinas', '(diisi dinas)'],
        'sifat'         => ['Sifat surat', '(diisi dinas)'],
        'tanggal_naskah' => ['Tanggal naskah', '(diisi dinas)'],
        'ttd_pengirim'  => ['Tempat tanda tangan elektronik', '(diisi dinas)'],
    ],
];