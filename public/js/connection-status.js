/*
 * Indikator status koneksi CRM Arsema.
 * Banner merah saat koneksi ke server putus, hijau sesaat ketika pulih.
 * Deteksi: event online/offline browser + ping berkala ke favicon (aset statis).
 */
(function () {
    'use strict';

    var PING_URL = '/favicon.ico';
    var PING_INTERVAL_OK = 30000;   // cek tiap 30 detik saat online
    var PING_INTERVAL_DOWN = 5000;  // coba ulang tiap 5 detik saat offline
    var PING_TIMEOUT = 8000;

    var isOffline = false;
    var banner, textEl, timerHide, timerPing;

    function injectStyle() {
        var css = ''
            + '#conn-status-banner{position:fixed;top:-60px;left:50%;transform:translateX(-50%);'
            + 'z-index:99999;display:flex;align-items:center;gap:10px;padding:10px 22px;'
            + 'border-radius:50px;font-family:"Segoe UI",Roboto,Arial,sans-serif;font-size:14px;'
            + 'font-weight:600;color:#fff;box-shadow:0 4px 18px rgba(0,0,0,.35);'
            + 'transition:top .4s cubic-bezier(.22,1,.36,1),background-color .3s;white-space:nowrap;}'
            + '#conn-status-banner.show{top:14px;}'
            + '#conn-status-banner.offline{background:#dc3545;}'
            + '#conn-status-banner.online{background:#28a745;}'
            + '#conn-status-banner .conn-dot{width:10px;height:10px;border-radius:50%;background:#fff;'
            + 'flex:0 0 auto;animation:conn-pulse 1.1s ease-in-out infinite;}'
            + '#conn-status-banner.online .conn-dot{animation:none;}'
            + '@keyframes conn-pulse{0%,100%{opacity:1;transform:scale(1);}50%{opacity:.35;transform:scale(.75);}}';
        var style = document.createElement('style');
        style.textContent = css;
        document.head.appendChild(style);
    }

    function ensureBanner() {
        if (banner) return;
        banner = document.createElement('div');
        banner.id = 'conn-status-banner';
        var dot = document.createElement('span');
        dot.className = 'conn-dot';
        textEl = document.createElement('span');
        banner.appendChild(dot);
        banner.appendChild(textEl);
        document.body.appendChild(banner);
    }

    function showOffline() {
        if (isOffline) return;
        isOffline = true;
        ensureBanner();
        clearTimeout(timerHide);
        banner.classList.remove('online');
        banner.classList.add('offline', 'show');
        textEl.textContent = 'Mode offline. Mencoba terhubung ke server...';
        schedulePing(PING_INTERVAL_DOWN);
    }

    function showOnline() {
        if (!isOffline) return;
        isOffline = false;
        ensureBanner();
        banner.classList.remove('offline');
        banner.classList.add('online', 'show');
        textEl.textContent = 'Online kembali';
        clearTimeout(timerHide);
        timerHide = setTimeout(function () {
            banner.classList.remove('show');
        }, 3000);
        schedulePing(PING_INTERVAL_OK);
    }

    function ping() {
        // navigator.onLine false = jelas offline, tidak perlu fetch
        if (!navigator.onLine) {
            showOffline();
            schedulePing(PING_INTERVAL_DOWN);
            return;
        }
        var controller = new AbortController();
        var t = setTimeout(function () { controller.abort(); }, PING_TIMEOUT);
        fetch(PING_URL + '?t=' + Date.now(), {
            method: 'HEAD',
            cache: 'no-store',
            signal: controller.signal
        }).then(function (res) {
            clearTimeout(t);
            if (res.ok) { showOnline(); schedulePing(PING_INTERVAL_OK); }
            else { showOffline(); }
        }).catch(function () {
            clearTimeout(t);
            showOffline();
        });
    }

    function schedulePing(delay) {
        clearTimeout(timerPing);
        timerPing = setTimeout(ping, delay);
    }

    function init() {
        injectStyle();
        window.addEventListener('offline', showOffline);
        window.addEventListener('online', ping);
        schedulePing(PING_INTERVAL_OK);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
