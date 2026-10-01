{{--
    Panel chat. Taruh SEKALI di navbar, tepat SETELAH </nav> (di luar <nav>
    supaya posisi fixed-nya mengikuti layar, bukan kotak navbar).
    Logika ada di public/js/ai-chatbot.js; semua tampilan gelembung ada di
    <template> bawah ini supaya class Tailwind-nya tetap terbaca saat build.
--}}
<div
    id="aiChatbotPanel"
    role="dialog"
    aria-label="Asisten MagangHub"
    class="hidden fixed top-24 right-3 sm:right-6 z-[95]
           w-[92vw] max-w-sm h-[70vh] max-h-[560px]
           bg-white rounded-2xl shadow-2xl border border-slate-200
           flex flex-col overflow-hidden"
>
    {{-- HEADER --}}
    <div class="flex items-center justify-between px-4 py-3
                bg-gradient-to-r from-indigo-600 to-blue-600 text-white shrink-0">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                          d="M12 8V4H8M12 8h4a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6a2 2 0 012-2h4z" />
                </svg>
            </div>
            <div>
                <p class="text-sm font-bold leading-none">Asisten MagangHub</p>
                <p class="text-[11px] text-indigo-100 mt-1">Ditenagai Gemini AI</p>
            </div>
        </div>

        <div class="flex items-center gap-1">
            <button type="button" data-ai-chatbot-reset title="Mulai percakapan baru"
                    class="px-2 h-7 rounded-full hover:bg-white/15 text-[11px] font-semibold">
                Mulai ulang
            </button>
            <button type="button" data-ai-chatbot-toggle aria-label="Tutup"
                    class="w-7 h-7 rounded-full hover:bg-white/15 flex items-center justify-center">
                ✕
            </button>
        </div>
    </div>

    {{-- AREA PESAN (anak pertama = sapaan, tidak ikut terhapus saat reset) --}}
    <div id="aiChatbotMessages" aria-live="polite"
         class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-slate-50 text-sm">
        <div class="flex">
            <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-3.5 py-2.5 max-w-[85%] text-slate-700 shadow-sm">
                Halo! 👋 Saya asisten AI MagangHub. Tanyakan apa saja seputar
                alur pendaftaran PKL/Magang, dokumen yang dibutuhkan, atau
                instansi yang tersedia.
            </div>
        </div>
    </div>

    {{-- INPUT --}}
    <form id="aiChatbotForm" data-endpoint="{{ route('chatbot.ask') }}"
          class="shrink-0 border-t border-slate-200 p-3 flex items-center gap-2 bg-white">
        <input
            id="aiChatbotInput"
            type="text"
            maxlength="1000"
            autocomplete="off"
            placeholder="Tulis pertanyaanmu..."
            class="flex-1 text-sm px-3.5 py-2.5 rounded-xl border border-slate-200
                   focus:outline-none focus:ring-2 focus:ring-blue-400 text-slate-700
                   disabled:bg-slate-100"
        />
        <button
            type="submit"
            aria-label="Kirim"
            class="w-10 h-10 shrink-0 rounded-xl bg-gradient-to-br from-indigo-600 to-blue-600
                   hover:from-indigo-700 hover:to-blue-700 text-white flex items-center justify-center
                   disabled:opacity-60"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
    </form>
</div>

{{-- Cetakan gelembung (diklon oleh JS) --}}
<template id="aiTplUser">
    <div class="flex justify-end">
        <div data-text class="whitespace-pre-line bg-blue-600 text-white rounded-2xl rounded-tr-sm px-3.5 py-2.5 max-w-[85%] shadow-sm"></div>
    </div>
</template>

<template id="aiTplBot">
    <div class="flex">
        <div data-text class="whitespace-pre-line bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-sm px-3.5 py-2.5 max-w-[85%] shadow-sm"></div>
    </div>
</template>

<template id="aiTplTyping">
    <div class="flex">
        <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-3.5 py-2.5 shadow-sm">
            <span class="inline-flex gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay:0ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay:150ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay:300ms"></span>
            </span>
        </div>
    </div>
</template>

<script src="{{ asset('js/ai-chatbot.js') }}" defer></script>