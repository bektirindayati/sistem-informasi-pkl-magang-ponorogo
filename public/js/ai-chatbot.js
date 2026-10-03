/**
 * Widget Asisten AI MagangHub.
 * Riwayat percakapan disimpan di sessionStorage (per TAB browser):
 *  - tutup lalu buka panel lagi / pindah halaman / reload -> masih ada
 *  - tab baru, browser ditutup, atau perangkat lain      -> mulai baru
 * Server tidak menyimpan percakapan sama sekali.
 */
(() => {
    'use strict';

    const STORAGE_KEY = 'magangHub.chat';      // riwayat percakapan (hapus juga saat logout)
    const TIP_KEY = 'magangHub.chat.tip';      // status tooltip
    const MAX_MESSAGES = 20;                   // 10 tanya-jawab terakhir

   const TIPS = [
    'Bingung atau butuh bantuan? Klik saya!',
    'Bingung cara daftar magang? Tanya saya',
    'Bingung atau butuh bantuan? Klik saya!',
    'Dokumen apa saja yang perlu disiapkan? Tanya saya',
    'Bingung atau butuh bantuan? Klik saya!',
    'Mau tahu instansi yang tersedia? Tanya saya',
];

const TIP_TIMING = {
    firstDelay: 4000,
    visibleFor: 7000,
    every: 10000
};

    const MSG = {
        server: 'Maaf, asisten AI sedang bermasalah. Coba lagi sebentar lagi ya.',
        network: 'Maaf, koneksi ke asisten AI gagal. Coba lagi ya.',
        tooMany: 'Terlalu banyak pesan, tunggu sebentar lalu coba lagi.',
    };

    const $ = (id) => document.getElementById(id);
    const panel = $('aiChatbotPanel');
    if (!panel) return;

    const launcher = $('aiChatbotLauncher');
    const list = $('aiChatbotMessages');
    const form = $('aiChatbotForm');
    const input = $('aiChatbotInput');
    const submitBtn = form.querySelector('button[type="submit"]');
    const tip = $('aiChatbotTip');
    const tipText = $('aiChatbotTipText');

    // ---------- penyimpanan (aman jika sessionStorage diblokir) ----------
    const store = {
        read(key, fallback) {
            try {
                const raw = sessionStorage.getItem(key);
                return raw ? JSON.parse(raw) : fallback;
            } catch (e) {
                return fallback;
            }
        },
        write(key, value) {
            try { sessionStorage.setItem(key, JSON.stringify(value)); } catch (e) { /* abaikan */ }
        },
        remove(key) {
            try { sessionStorage.removeItem(key); } catch (e) { /* abaikan */ }
        },
    };

    // ---------- riwayat ----------
    let history = loadHistory();
    let busy = false;
    let typingNode = null;

    function loadHistory() {
        const saved = store.read(STORAGE_KEY, []);
        if (!Array.isArray(saved)) return [];
        return saved
            .filter((m) => m && ['user', 'model'].includes(m.role) && typeof m.text === 'string')
            .slice(-MAX_MESSAGES);
    }

    function remember(question, answer) {
        history.push({ role: 'user', text: question }, { role: 'model', text: answer });
        history = history.slice(-MAX_MESSAGES);
        store.write(STORAGE_KEY, history);
    }

    function resetChat() {
        history = [];
        store.remove(STORAGE_KEY);
        while (list.children.length > 1) list.lastElementChild.remove(); // sisakan sapaan
        input.focus();
    }

    // ---------- tampilan ----------
    function addMessage(text, role) {
        const node = $(role === 'user' ? 'aiTplUser' : 'aiTplBot').content.firstElementChild.cloneNode(true);
        node.querySelector('[data-text]').textContent = text;
        list.appendChild(node);
        scrollDown();
    }

    function showTyping() {
        typingNode = $('aiTplTyping').content.firstElementChild.cloneNode(true);
        list.appendChild(typingNode);
        scrollDown();
    }

    function hideTyping() {
        typingNode?.remove();
        typingNode = null;
    }

    function scrollDown() {
        list.scrollTop = list.scrollHeight;
    }

    function setBusy(state) {
        busy = state;
        input.disabled = state;
        submitBtn.disabled = state;
    }

    const isOpen = () => !panel.classList.contains('hidden');

    function setOpen(open) {
        panel.classList.toggle('hidden', !open);
        launcher?.setAttribute('aria-expanded', String(open));

        if (open) {
            stopTips();
            scrollDown();
            input.focus();
        }
    }

    // ---------- kirim pesan ----------
    async function onSubmit(event) {
        event.preventDefault();

        const message = input.value.trim();
        if (!message || busy) return;

        addMessage(message, 'user');
        input.value = '';
        setBusy(true);
        showTyping();

        try {
            const res = await fetch(form.dataset.endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? '',
                },
               body: JSON.stringify({
    message: message,
    history: history,
}),
            });
            const data = await res.json().catch(() => ({}));
            const reply = data.reply || (res.status === 429 ? MSG.tooMany : MSG.server);

            hideTyping();
            addMessage(reply, 'model');

            // Hanya jawaban sukses yang masuk riwayat, supaya pesan error
            // tidak ikut dikirim sebagai konteks percakapan.
            if (res.ok) remember(message, reply);
        } catch (e) {
            hideTyping();
            addMessage(MSG.network, 'model');
        } finally {
            setBusy(false);
            input.focus();
        }
    }

    // ---------- tooltip berkala ----------
    let tipTimer = null;
    const tipState = store.read(TIP_KEY, { done: false, next: 0 });

    function scheduleTip(delay) {
        clearTimeout(tipTimer);
        if (!tipState.done) tipTimer = setTimeout(showTip, delay);
    }

    function showTip() {
        if (tipState.done) return;

        // Jangan ganggu: panel sedang terbuka atau tab sedang tidak dilihat.
        if (isOpen() || document.hidden) {
            scheduleTip(TIP_TIMING.every);
            return;
        }

        tipText.textContent = TIPS[tipState.next % TIPS.length];
        tipState.next = (tipState.next + 1) % TIPS.length;
        store.write(TIP_KEY, tipState);

        tip.dataset.visible = 'true';
        tipTimer = setTimeout(() => {
            tip.dataset.visible = 'false';
            scheduleTip(TIP_TIMING.every);
        }, TIP_TIMING.visibleFor);
    }

    /** Berhenti selamanya (selama tab ini): user sudah membuka chat atau menutup saran. */
    function stopTips() {
        tipState.done = true;
        store.write(TIP_KEY, tipState);
        clearTimeout(tipTimer);
        tip.dataset.visible = 'false';
    }

    // ---------- pasang ----------
    history.forEach((m) => addMessage(m.text, m.role));

    document.addEventListener('click', (e) => {
        if (e.target.closest('[data-ai-chatbot-toggle]')) setOpen(!isOpen());
        else if (e.target.closest('[data-ai-chatbot-tip-dismiss]')) stopTips();
        else if (e.target.closest('[data-ai-chatbot-reset]')) resetChat();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen()) {
            setOpen(false);
            launcher?.focus();
        }
    });

    form.addEventListener('submit', onSubmit);

    // Tombol lama yang masih memanggil toggleAiChatbot() tetap berfungsi.
    window.toggleAiChatbot = () => setOpen(!isOpen());

    if (history.length > 0) stopTips(); // sudah pernah chat: tidak perlu diajak lagi
    else scheduleTip(TIP_TIMING.firstDelay);
})();