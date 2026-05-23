<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Raspberry Pi Monitor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600&family=Sora:wght@300;400;600;700&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #0a0e1a;
            --bg-card: #111827;
            --bg-card2: #151e2e;
            --border: rgba(255, 255, 255, 0.07);
            --border-glow: rgba(56, 189, 248, 0.3);
            --accent: #38bdf8;
            --accent2: #6ee7b7;
            --accent3: #f472b6;
            --text: #f1f5f9;
            --muted: #64748b;
            --muted2: #94a3b8;
            --font: 'Sora', sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --radius: 14px;
            --radius-sm: 8px;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Subtle animated grid bg */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(56, 189, 248, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56, 189, 248, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        .shell {
            position: relative;
            z-index: 1;
            max-width: 1280px;
            margin: 0 auto;
            padding: 28px 24px 48px;
        }

        /* ── TOPBAR ── */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: linear-gradient(135deg, #1e40af, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg {
            width: 22px;
            height: 22px;
            fill: white;
        }

        .brand-text h1 {
            font-size: 17px;
            font-weight: 700;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 12px;
            color: var(--muted);
            font-family: var(--mono);
            margin-top: 1px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .pill-live {
            display: flex;
            align-items: center;
            gap: 6px;
            background: rgba(110, 231, 183, 0.1);
            border: 1px solid rgba(110, 231, 183, 0.2);
            border-radius: 999px;
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            color: var(--accent2);
            font-family: var(--mono);
        }

        .dot-live {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--accent2);
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.5;
                transform: scale(0.75);
            }
        }

        .pill-ip {
            background: rgba(56, 189, 248, 0.08);
            border: 1px solid rgba(56, 189, 248, 0.18);
            border-radius: 999px;
            padding: 5px 12px;
            font-size: 12px;
            color: var(--accent);
            font-family: var(--mono);
        }

        /* ── STAT ROW ── */
        .stat-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 14px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 16px 18px;
            position: relative;
            overflow: hidden;
            transition: border-color 0.2s;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            border-radius: 2px 2px 0 0;
        }

        .stat-card.blue::before {
            background: linear-gradient(90deg, #38bdf8, #818cf8);
        }

        .stat-card.green::before {
            background: linear-gradient(90deg, #6ee7b7, #34d399);
        }

        .stat-card.pink::before {
            background: linear-gradient(90deg, #f472b6, #c084fc);
        }

        .stat-card.amber::before {
            background: linear-gradient(90deg, #fbbf24, #fb923c);
        }

        .stat-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            font-family: var(--mono);
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 22px;
            font-weight: 700;
            font-family: var(--mono);
            letter-spacing: -0.5px;
            color: var(--text);
            line-height: 1;
        }

        .stat-value span {
            font-size: 13px;
            font-weight: 400;
            color: var(--muted2);
            margin-left: 3px;
        }

        /* ── MAIN GRID ── */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 20px;
            align-items: start;
        }

        @media (max-width: 960px) {
            .main-grid {
                grid-template-columns: 1fr;
            }
        }

        /* ── CARD BASE ── */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
        }

        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .card-title {
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: var(--muted2);
            font-family: var(--mono);
        }

        .card-badge {
            font-size: 11px;
            font-family: var(--mono);
            padding: 3px 9px;
            border-radius: 999px;
            background: rgba(56, 189, 248, 0.1);
            border: 1px solid rgba(56, 189, 248, 0.2);
            color: var(--accent);
        }

        /* ── CAMERA ── */
        .camera-wrap {
            position: relative;
            background: #000;
        }

        .camera-wrap img {
            width: 100%;
            display: block;
            min-height: 300px;
            object-fit: cover;
        }

        .camera-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        /* Corner brackets */
        .camera-overlay::before,
        .camera-overlay::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            border-color: var(--accent);
            border-style: solid;
        }

        .camera-overlay::before {
            top: 12px;
            left: 12px;
            border-width: 2px 0 0 2px;
        }

        .camera-overlay::after {
            bottom: 12px;
            right: 12px;
            border-width: 0 2px 2px 0;
        }

        .cam-rec {
            position: absolute;
            top: 12px;
            right: 12px;
            display: flex;
            align-items: center;
            gap: 5px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
            border-radius: 999px;
            padding: 4px 10px;
            font-size: 11px;
            font-family: var(--mono);
            color: #f87171;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .cam-rec-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #f87171;
            animation: pulse-dot 1s ease-in-out infinite;
        }

        .cam-time {
            position: absolute;
            bottom: 12px;
            left: 12px;
            font-size: 11px;
            font-family: var(--mono);
            color: rgba(255, 255, 255, 0.7);
            background: rgba(0, 0, 0, 0.5);
            padding: 3px 8px;
            border-radius: 4px;
        }

        /* ── GPS PANEL ── */
        .gps-side {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .gps-status-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: var(--bg-card2);
        }

        .gps-status-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .gps-status-icon svg {
            width: 16px;
            height: 16px;
        }

        .gps-status-text {
            font-size: 13px;
            font-weight: 600;
        }

        .gps-status-sub {
            font-size: 11px;
            color: var(--muted);
            font-family: var(--mono);
        }

        #gps-status-wrap.active .gps-status-icon {
            background: rgba(110, 231, 183, 0.15);
        }

        #gps-status-wrap.active .gps-status-icon svg {
            stroke: #6ee7b7;
        }

        #gps-status-wrap.active .gps-status-text {
            color: #6ee7b7;
        }

        #gps-status-wrap.error .gps-status-icon {
            background: rgba(248, 113, 113, 0.15);
        }

        #gps-status-wrap.error .gps-status-icon svg {
            stroke: #f87171;
        }

        #gps-status-wrap.error .gps-status-text {
            color: #f87171;
        }

        #gps-status-wrap.loading .gps-status-icon {
            background: rgba(56, 189, 248, 0.15);
        }

        #gps-status-wrap.loading .gps-status-icon svg {
            stroke: var(--accent);
        }

        #gps-status-wrap.loading .gps-status-text {
            color: var(--accent);
        }

        /* GPS data grid */
        .gps-data-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            padding: 16px 20px;
        }

        .gps-field {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            padding: 12px 14px;
        }

        .gps-field-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--muted);
            font-family: var(--mono);
            margin-bottom: 6px;
        }

        .gps-field-value {
            font-size: 15px;
            font-weight: 600;
            font-family: var(--mono);
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Map */
        .map-wrap {
            margin: 0 20px 20px;
            border-radius: var(--radius-sm);
            overflow: hidden;
            border: 1px solid var(--border);
            position: relative;
        }

        .map-wrap iframe {
            width: 100%;
            height: 220px;
            display: block;
            border: 0;
            filter: invert(0.9) hue-rotate(180deg) saturate(0.8);
        }

        .map-placeholder {
            height: 220px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.02);
            color: var(--muted);
            font-size: 13px;
            font-family: var(--mono);
        }

        .map-placeholder svg {
            width: 28px;
            height: 28px;
            stroke: var(--muted);
            opacity: 0.5;
        }

        /* ── FOOTER ── */
        .footer {
            margin-top: 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            border-top: 1px solid var(--border);
            padding-top: 20px;
            font-size: 12px;
            color: var(--muted);
            font-family: var(--mono);
        }

        .footer-right {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        #last-update {
            color: var(--muted2);
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 600px) {
            .shell {
                padding: 16px 14px 40px;
            }

            .stat-row {
                grid-template-columns: 1fr 1fr;
            }

            .gps-data-grid {
                grid-template-columns: 1fr 1fr;
            }

            .brand-text h1 {
                font-size: 15px;
            }

            .topbar-right .pill-ip {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="shell">

        <!-- TOPBAR -->
        <div class="topbar">
            <div class="brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z" />
                    </svg>
                </div>
                <div class="brand-text">
                    <h1>Raspberry Pi Monitor</h1>
                    <p>Realtime · Webcam + GPS NEO M8N</p>
                </div>
            </div>
            <div class="topbar-right">
                <div class="pill-ip">{{ $raspberryIp }}</div>
                <div class="pill-live">
                    <div class="dot-live"></div>
                    LIVE
                </div>
            </div>
        </div>

        <!-- STAT ROW -->
        <div class="stat-row">
            <div class="stat-card blue">
                <div class="stat-label">Latitude</div>
                <div class="stat-value" id="s-lat">—</div>
            </div>
            <div class="stat-card green">
                <div class="stat-label">Longitude</div>
                <div class="stat-value" id="s-lng">—</div>
            </div>
            <div class="stat-card pink">
                <div class="stat-label">Altitude</div>
                <div class="stat-value" id="s-alt">—<span>m</span></div>
            </div>
            <div class="stat-card amber">
                <div class="stat-label">Speed</div>
                <div class="stat-value" id="s-spd">—<span>m/s</span></div>
            </div>
        </div>

        <!-- MAIN GRID -->
        <div class="main-grid">

            <!-- CAMERA CARD -->
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Kamera Realtime</div>
                    <div class="card-badge">mjpeg · 8080</div>
                </div>
                <div class="camera-wrap">
                    <img src="http://{{ $raspberryIp }}:8080/?action=stream" alt="Webcam Stream" id="cam-img">
                    <div class="camera-overlay"></div>
                    <div class="cam-rec">
                        <div class="cam-rec-dot"></div>
                        REC
                    </div>
                    <div class="cam-time" id="cam-time">--:--:--</div>
                </div>
            </div>

            <!-- GPS SIDE -->
            <div class="gps-side">

                <!-- GPS STATUS CARD -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">GPS NEO M8N</div>
                        <div class="card-badge" id="gps-fix-label">fix: —</div>
                    </div>

                    <div class="gps-status-bar" id="gps-status-wrap" class="loading">
                        <div class="gps-status-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10" />
                                <circle cx="12" cy="12" r="3" />
                                <line x1="12" y1="2" x2="12" y2="6" />
                                <line x1="12" y1="18" x2="12" y2="22" />
                                <line x1="2" y1="12" x2="6" y2="12" />
                                <line x1="18" y1="12" x2="22" y2="12" />
                            </svg>
                        </div>
                        <div>
                            <div class="gps-status-text" id="gps-status-text">Mengambil data...</div>
                            <div class="gps-status-sub" id="gps-status-sub">port :5000 · /api/gps</div>
                        </div>
                    </div>

                    <div class="gps-data-grid">
                        <div class="gps-field">
                            <div class="gps-field-label">Latitude</div>
                            <div class="gps-field-value" id="latitude">—</div>
                        </div>
                        <div class="gps-field">
                            <div class="gps-field-label">Longitude</div>
                            <div class="gps-field-value" id="longitude">—</div>
                        </div>
                        <div class="gps-field">
                            <div class="gps-field-label">Altitude</div>
                            <div class="gps-field-value" id="altitude">—</div>
                        </div>
                        <div class="gps-field">
                            <div class="gps-field-label">Speed</div>
                            <div class="gps-field-value" id="speed">—</div>
                        </div>
                    </div>
                </div>

                <!-- MAP CARD -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Peta Lokasi</div>
                        <div class="card-badge" id="map-coords-label">menunggu fix...</div>
                    </div>
                    <div class="map-wrap">
                        <div class="map-placeholder" id="map-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            Menunggu sinyal GPS...
                        </div>
                        <iframe id="map" src="" style="display:none" allowfullscreen
                            loading="lazy"></iframe>
                    </div>
                </div>

            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <span>Raspberry Pi Dashboard &mdash; GPS NEO M8N + mjpeg-streamer</span>
            <div class="footer-right">
                <span>Update terakhir:</span>
                <span id="last-update">—</span>
            </div>
        </div>

    </div>

    <script>
        const raspberryIp = "{{ $raspberryIp }}";
        const gpsUrl = `http://${raspberryIp}:5000/api/gps`;

        // Clock
        function updateClock() {
            const now = new Date();
            const pad = n => String(n).padStart(2, '0');
            document.getElementById('cam-time').textContent =
                `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        function setStatus(type, text, sub) {
            const wrap = document.getElementById('gps-status-wrap');
            wrap.className = type;
            document.getElementById('gps-status-text').textContent = text;
            if (sub) document.getElementById('gps-status-sub').textContent = sub;
        }

        function fmt(val, decimals = 6) {
            if (val == null || val === '') return '—';
            return parseFloat(val).toFixed(decimals);
        }

        async function loadGps() {
            try {
                const response = await fetch(gpsUrl);
                const data = await response.json();

                if (data.status !== 'ok') {
                    setStatus('error', data.message || 'GPS belum fix', 'Sinyal tidak tersedia');
                    document.getElementById('gps-fix-label').textContent = 'fix: ✗';
                    return;
                }

                setStatus('active', 'GPS Aktif', `lat ${fmt(data.latitude,4)} · lng ${fmt(data.longitude,4)}`);
                document.getElementById('gps-fix-label').textContent = 'fix: ✓';

                // Stat bar
                document.getElementById('s-lat').innerHTML = `${fmt(data.latitude)}`;
                document.getElementById('s-lng').innerHTML = `${fmt(data.longitude)}`;
                document.getElementById('s-alt').innerHTML = `${data.altitude ?? '—'}<span>m</span>`;
                document.getElementById('s-spd').innerHTML = `${data.speed ?? 0}<span>m/s</span>`;

                // Detail
                document.getElementById('latitude').textContent = fmt(data.latitude);
                document.getElementById('longitude').textContent = fmt(data.longitude);
                document.getElementById('altitude').textContent = `${data.altitude ?? '—'} m`;
                document.getElementById('speed').textContent = `${data.speed ?? 0} m/s`;

                // Map
                const mapSrc = `https://maps.google.com/maps?q=${data.latitude},${data.longitude}&z=16&output=embed`;
                const iframe = document.getElementById('map');
                const placeholder = document.getElementById('map-placeholder');
                if (iframe.src !== mapSrc) {
                    iframe.src = mapSrc;
                    iframe.style.display = 'block';
                    placeholder.style.display = 'none';
                }
                document.getElementById('map-coords-label').textContent =
                    `${fmt(data.latitude,4)}, ${fmt(data.longitude,4)}`;

            } catch (error) {
                setStatus('error', 'Gagal konek ke Raspberry Pi', error.message || '');
                document.getElementById('gps-fix-label').textContent = 'fix: ✗';
            }

            // Timestamp
            const now = new Date();
            document.getElementById('last-update').textContent = now.toLocaleTimeString('id-ID');
        }

        loadGps();
        setInterval(loadGps, 3000);
    </script>

</body>

</html>
