#!/usr/bin/env node
/**
 * dark-migrate.mjs — tambah pasangan kelas `dark:` otomatis di file Blade.
 *
 * Letakkan di ROOT proyek Laravel (sejajar folder `resources/`), lalu:
 *
 *   node dark-migrate.mjs --dry     # simulasi: hanya tampilkan ringkasan, tidak mengubah file
 *   node dark-migrate.mjs           # terapkan ke resources/views
 *   node dark-migrate.mjs resources/views/user   # terapkan ke satu folder saja
 *
 * SEBELUM MENERAPKAN: commit dulu ke git (git add . && git commit -m "sebelum dark mode")
 * supaya hasilnya bisa ditinjau lewat `git diff` dan dibatalkan lewat `git checkout .`.
 *
 * Aman dijalankan berulang: token yang sudah diikuti kelas `dark:` dilewati.
 * Ini PASS PERTAMA — tetap cek hasilnya di browser, lalu rapikan manual yang kurang pas.
 */
import fs from 'node:fs';
import path from 'node:path';

const args = process.argv.slice(2);
const DRY = args.includes('--dry');
const target = args.find(a => !a.startsWith('--')) || 'resources/views';

// File yang SENGAJA dilewati (cetak/PDF/email tidak boleh ikut gelap)
const SKIP_PATH = /(surat|pdf|cetak|print|emails?|partials[\\/](theme-script|tailwind-tema))/i;

const colors = ['blue', 'cyan', 'sky', 'indigo', 'emerald', 'amber', 'orange', 'rose', 'red'];

/** [token terang] -> kelas dark. Nilai berisi "/" = tetap (opacity token asli diabaikan). */
const map = {
  // permukaan
  'bg-white': 'dark:bg-slate-800',
  'bg-slate-50': 'dark:bg-slate-900/50',
  'bg-slate-100': 'dark:bg-slate-700',
  'bg-slate-200': 'dark:bg-slate-600',
  'hover:bg-white': 'dark:hover:bg-slate-700',
  'hover:bg-slate-50': 'dark:hover:bg-slate-700',
  'hover:bg-slate-100': 'dark:hover:bg-slate-700',
  'hover:bg-slate-200': 'dark:hover:bg-slate-600',
  // garis
  'border-slate-100': 'dark:border-slate-700',
  'border-slate-200': 'dark:border-slate-700',
  'border-slate-300': 'dark:border-slate-600',
  'divide-slate-100': 'dark:divide-slate-700',
  'divide-slate-200': 'dark:divide-slate-700',
  // teks
  'text-slate-900': 'dark:text-white',
  'text-slate-800': 'dark:text-slate-100',
  'text-slate-700': 'dark:text-slate-200',
  'text-slate-600': 'dark:text-slate-300',
  'text-slate-500': 'dark:text-slate-400',
  'hover:text-slate-800': 'dark:hover:text-slate-100',
  'hover:text-slate-700': 'dark:hover:text-slate-200',
  // gradasi lembut
  'via-white': 'dark:via-slate-800',
};

for (const c of colors) {
  map[`bg-${c}-50`] = `dark:bg-${c}-500/10`;
  map[`bg-${c}-100`] = `dark:bg-${c}-500/20`;
  map[`hover:bg-${c}-50`] = `dark:hover:bg-${c}-500/10`;
  map[`hover:bg-${c}-100`] = `dark:hover:bg-${c}-500/20`;
  map[`border-${c}-100`] = `dark:border-${c}-500/20`;
  map[`border-${c}-200`] = `dark:border-${c}-500/30`;
  map[`hover:border-${c}-300`] = `dark:hover:border-${c}-500/50`;
  map[`text-${c}-600`] = `dark:text-${c}-400`;
  map[`text-${c}-700`] = `dark:text-${c}-300`;
  map[`text-${c}-800`] = `dark:text-${c}-300`;
  map[`hover:text-${c}-600`] = `dark:hover:text-${c}-400`;
  map[`hover:text-${c}-700`] = `dark:hover:text-${c}-300`;
  map[`from-${c}-50`] = 'dark:from-slate-800';
  map[`to-${c}-50`] = 'dark:to-slate-800';
}

const esc = s => s.replace(/[.*+?^${}()|[\]\\/]/g, '\\$&');
// urutkan: token terpanjang dulu agar "hover:bg-slate-50" tidak kalah dari "bg-slate-50"
const keys = Object.keys(map).sort((a, b) => b.length - a.length);
const TOKEN = new RegExp(
  `(?<![\\w:./\\[-])(${keys.map(esc).join('|')})(\\/\\d+)?(?![\\w-])(?!\\s+dark:)`,
  'g'
);

function transform(src) {
  let count = 0;
  const out = src.replace(TOKEN, (m, base, opacity) => {
    const dark = map[base];
    const hasSlash = dark.includes('/');
    const darkClass = hasSlash ? dark : dark + (opacity || '');
    count++;
    return `${m} ${darkClass}`;
  });
  return { out, count };
}

function* walk(dir) {
  for (const e of fs.readdirSync(dir, { withFileTypes: true })) {
    const p = path.join(dir, e.name);
    if (e.isDirectory()) yield* walk(p);
    else if (e.name.endsWith('.blade.php')) yield p;
  }
}

if (!fs.existsSync(target)) {
  console.error(`Folder tidak ditemukan: ${target}\nJalankan dari root proyek Laravel.`);
  process.exit(1);
}

let total = 0, filesChanged = 0;
const skipped = [];
const rows = [];

for (const file of walk(target)) {
  if (SKIP_PATH.test(file)) { skipped.push(file); continue; }
  const src = fs.readFileSync(file, 'utf8');
  const { out, count } = transform(src);
  if (count > 0) {
    filesChanged++; total += count;
    rows.push([count, file]);
    if (!DRY) fs.writeFileSync(file, out, 'utf8');
  }
}

rows.sort((a, b) => b[0] - a[0]).forEach(([n, f]) => console.log(String(n).padStart(5), ' ', f));
console.log(`\n${DRY ? '[SIMULASI] ' : ''}${total} kelas dark: ${DRY ? 'akan ditambahkan' : 'ditambahkan'} di ${filesChanged} file.`);
if (skipped.length) {
  console.log(`\nDilewati (cetak/PDF/email/partial):`);
  skipped.forEach(f => console.log('  - ' + f));
}
if (DRY) console.log('\nTidak ada file yang diubah. Jalankan tanpa --dry untuk menerapkan.');
