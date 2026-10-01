{{--
    Tombol robot + tooltip ajakan. Taruh DI DALAM navbar:
        <x-ai-chatbot-launcher />
    Panel chat-nya ada di <x-ai-chatbot-modal /> (letakkan di luar <nav>).
    Perilaku (buka/tutup, tooltip berkala) diatur public/js/ai-chatbot.js.
--}}
<div class="relative shrink-0">
    <button
    type="button"
    id="aiChatbotLauncher"
    onclick="toggleAiChatbot()"
        aria-controls="aiChatbotPanel"
        aria-expanded="false"
        aria-label="Buka Asisten AI MagangHub"
        title="Tanya Asisten AI MagangHub"
        class="relative w-9 h-9 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl
               bg-gradient-to-br from-indigo-600 to-blue-600
               hover:from-indigo-700 hover:to-blue-700
               text-white flex items-center justify-center
               shadow-md shadow-indigo-500/25 transition-all duration-200"
    >
        <span class="absolute -top-1 -right-1 flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-300 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-400 border-2 border-white"></span>
        </span>

        <svg class="w-4 h-4 sm:w-5 sm:h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                  d="M12 8V4H8M12 8h4a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6a2 2 0 012-2h4z" />
            <circle cx="9" cy="13" r="1" fill="currentColor" stroke="none" />
            <circle cx="15" cy="13" r="1" fill="currentColor" stroke="none" />
        </svg>
    </button>

    {{-- TOOLTIP: teksnya diisi & ditampilkan berkala oleh JS --}}
    <div id="aiChatbotTip" role="status" data-visible="false"
         class="absolute right-0 top-full mt-3 w-56 z-10">
        <div class="relative flex items-start gap-1 rounded-2xl bg-white border border-slate-200 shadow-lg pl-3.5 pr-1.5 py-2.5">
            <span class="absolute -top-1.5 right-3 w-3 h-3 rotate-45 bg-white border-l border-t border-slate-200"></span>

            <button type="button" data-ai-chatbot-toggle
                    class="flex-1 text-left text-xs font-semibold text-slate-700 leading-snug">
                <span id="aiChatbotTipText"></span>
            </button>

            <button type="button" data-ai-chatbot-tip-dismiss aria-label="Tutup saran"
                    class="shrink-0 w-5 h-5 text-xs leading-none text-slate-400 hover:text-slate-600">
                ✕
            </button>
        </div>
    </div>

    <style>
        #aiChatbotTip {
            opacity: 0;
            visibility: hidden;
            transform: translateY(4px);
            transition: opacity .25s ease, transform .25s ease, visibility .25s;
        }
        #aiChatbotTip[data-visible="true"] {
            opacity: 1;
            visibility: visible;
            transform: none;
        }
        @media (prefers-reduced-motion: reduce) {
            #aiChatbotTip { transition: none; }
        }
    </style>
</div>