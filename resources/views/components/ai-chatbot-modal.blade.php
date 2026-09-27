{{--
    Taruh <x-ai-chatbot-modal /> SEKALI SAJA, di layout utama
    (misalnya di user/layout/app.blade.php, tepat sebelum </body>,
    atau langsung di dalam navbar.blade.php).

    Popup ini melayang (fixed) di pojok kanan atas, tidak menutupi
    seluruh layar.
--}}
<div
    id="aiChatbotPanel"
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
        <button type="button" onclick="toggleAiChatbot()" class="w-7 h-7 rounded-full hover:bg-white/15 flex items-center justify-center">
            ✕
        </button>
    </div>

    {{-- AREA PESAN --}}
    <div id="aiChatbotMessages" class="flex-1 overflow-y-auto px-4 py-4 space-y-3 bg-slate-50 text-sm">
        <div class="flex">
            <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-3.5 py-2.5 max-w-[85%] text-slate-700 shadow-sm">
                Halo! 👋 Saya asisten AI MagangHub. Tanyakan apa saja seputar
                alur pendaftaran PKL/Magang, dokumen yang dibutuhkan, atau
                instansi yang tersedia.
            </div>
        </div>
    </div>

    {{-- INPUT --}}
    <form id="aiChatbotForm" class="shrink-0 border-t border-slate-200 p-3 flex items-center gap-2 bg-white">
        <input
            id="aiChatbotInput"
            type="text"
            autocomplete="off"
            placeholder="Tulis pertanyaanmu..."
            class="flex-1 text-sm px-3.5 py-2.5 rounded-xl border border-slate-200
                   focus:outline-none focus:ring-2 focus:ring-blue-400 text-slate-700"
        />
        <button
            type="submit"
            class="w-10 h-10 shrink-0 rounded-xl bg-gradient-to-br from-indigo-600 to-blue-600
                   hover:from-indigo-700 hover:to-blue-700 text-white flex items-center justify-center"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </button>
    </form>
</div>

<script>
    // --- state percakapan (dikirim balik ke server tiap request
    //     supaya AI "ingat" konteks chat sebelumnya) ---
    let aiChatHistory = [];

    function toggleAiChatbot() {
        const panel = document.getElementById('aiChatbotPanel');
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            document.getElementById('aiChatbotInput').focus();
        }
    }

    function aiAppendMessage(text, role) {
        const wrap = document.getElementById('aiChatbotMessages');
        const row = document.createElement('div');
        row.className = 'flex ' + (role === 'user' ? 'justify-end' : '');

        const bubble = document.createElement('div');
        bubble.className = role === 'user'
            ? 'bg-blue-600 text-white rounded-2xl rounded-tr-sm px-3.5 py-2.5 max-w-[85%] shadow-sm'
            : 'bg-white border border-slate-200 text-slate-700 rounded-2xl rounded-tl-sm px-3.5 py-2.5 max-w-[85%] shadow-sm';
        bubble.style.whiteSpace = 'pre-line';
        bubble.textContent = text;

        row.appendChild(bubble);
        wrap.appendChild(row);
        wrap.scrollTop = wrap.scrollHeight;
        return row;
    }

    function aiShowTyping() {
        const wrap = document.getElementById('aiChatbotMessages');
        const row = document.createElement('div');
        row.id = 'aiTypingIndicator';
        row.className = 'flex';
        row.innerHTML = `<div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-3.5 py-2.5 shadow-sm">
            <span class="inline-flex gap-1">
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay:0ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay:150ms"></span>
                <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce" style="animation-delay:300ms"></span>
            </span>
        </div>`;
        wrap.appendChild(row);
        wrap.scrollTop = wrap.scrollHeight;
    }

    function aiRemoveTyping() {
        document.getElementById('aiTypingIndicator')?.remove();
    }

    document.getElementById('aiChatbotForm').addEventListener('submit', async function (e) {
        e.preventDefault();
        const input = document.getElementById('aiChatbotInput');
        const message = input.value.trim();
        if (!message) return;

        aiAppendMessage(message, 'user');
        input.value = '';
        aiShowTyping();

        try {
            const res = await fetch('{{ route('chatbot.ask') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ message: message, history: aiChatHistory }),
            });
            const data = await res.json();

            aiRemoveTyping();
            aiAppendMessage(data.reply, 'model');

            // simpan ke history (maks 10 pertukaran terakhir biar ringan)
            aiChatHistory.push({ role: 'user', text: message });
            aiChatHistory.push({ role: 'model', text: data.reply });
            aiChatHistory = aiChatHistory.slice(-20);
        } catch (err) {
            aiRemoveTyping();
            aiAppendMessage('Maaf, koneksi ke asisten AI gagal. Coba lagi ya.', 'model');
        }
    });
</script>