<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Arunavastra — MarineHyProjects</title>

    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Socket.io -->
    <script src="/socket.io/socket.io.js"></script>

    <!-- Google Fonts -->
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
            --bg: #080d18;
            --bg-card: #0f1724;
            --bg-card2: #131c2b;
            --border: rgba(255, 255, 255, 0.07);
            --border2: rgba(255, 255, 255, 0.12);
            --text: #f1f5f9;
            --muted: #64748b;
            --muted2: #94a3b8;
            --accent: #38bdf8;
            --green: #6ee7b7;
            --pink: #f472b6;
            --amber: #fbbf24;
            --red: #f87171;
            --font: 'Sora', sans-serif;
            --mono: 'JetBrains Mono', monospace;
            --r: 12px;
            --rs: 8px;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* subtle grid overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(56, 189, 248, 0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(56, 189, 248, 0.025) 1px, transparent 1px);
            background-size: 44px 44px;
            pointer-events: none;
            z-index: 0;
        }

        /* ── TOPBAR ── */
        header {
            position: relative;
            z-index: 10;
            flex-shrink: 0;
            height: 58px;
            background: var(--bg-card);
            border-bottom: 0.5px solid var(--border2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 18px;
            gap: 12px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: var(--rs);
            background: linear-gradient(135deg, #1e40af, #0ea5e9);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-icon svg {
            width: 18px;
            height: 18px;
            fill: white;
        }

        .brand-name {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: -0.3px;
            color: var(--text);
        }

        .brand-sub {
            font-size: 10px;
            color: var(--muted);
            font-family: var(--mono);
            margin-top: 1px;
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .pill {
            display: flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            padding: 5px 12px;
            font-size: 11px;
            font-family: var(--mono);
            font-weight: 600;
            border: 0.5px solid;
        }

        .pill-live {
            background: rgba(110, 231, 183, 0.08);
            border-color: rgba(110, 231, 183, 0.2);
            color: var(--green);
        }

        .pill-conn {
            background: rgba(56, 189, 248, 0.08);
            border-color: rgba(56, 189, 248, 0.2);
            color: var(--accent);
            transition: all .3s;
        }

        .pill-conn.ok {
            background: rgba(110, 231, 183, 0.08);
            border-color: rgba(110, 231, 183, 0.2);
            color: var(--green);
        }

        .pill-conn.err {
            background: rgba(248, 113, 113, 0.08);
            border-color: rgba(248, 113, 113, 0.2);
            color: var(--red);
        }

        .dot-pulse {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            animation: pdot 1.4s ease-in-out infinite;
        }

        @keyframes pdot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .4;
                transform: scale(.7);
            }
        }

        /* ── STAT ROW ── */
        .stat-row {
            position: relative;
            z-index: 1;
            flex-shrink: 0;
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 0;
            background: var(--bg-card);
            border-bottom: 0.5px solid var(--border2);
        }

        .stat-cell {
            padding: 10px 16px;
            border-right: 0.5px solid var(--border);
            position: relative;
        }

        .stat-cell:last-child {
            border-right: none;
        }

        .stat-cell::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
        }

        .sc-blue::after {
            background: linear-gradient(90deg, #38bdf8, #818cf8);
        }

        .sc-green::after {
            background: linear-gradient(90deg, #6ee7b7, #34d399);
        }

        .sc-pink::after {
            background: linear-gradient(90deg, #f472b6, #c084fc);
        }

        .sc-amber::after {
            background: linear-gradient(90deg, #fbbf24, #fb923c);
        }

        .sc-teal::after {
            background: linear-gradient(90deg, #2dd4bf, #0ea5e9);
        }

        .sc-red::after {
            background: linear-gradient(90deg, #f87171, #fb923c);
        }

        .stat-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .8px;
            color: var(--muted);
            font-family: var(--mono);
            margin-bottom: 3px;
        }

        .stat-val {
            font-size: 17px;
            font-weight: 700;
            font-family: var(--mono);
            letter-spacing: -.5px;
            color: var(--text);
            line-height: 1;
        }

        .stat-val span {
            font-size: 11px;
            font-weight: 400;
            color: var(--muted2);
            margin-left: 2px;
        }

        /* ── MAIN ── */
        .main {
            position: relative;
            z-index: 1;
            flex: 1;
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 10px;
            padding: 10px;
            min-height: 0;
        }

        /* ── CARD ── */
        .card {
            background: var(--bg-card);
            border: 0.5px solid var(--border2);
            border-radius: var(--r);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .card-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-bottom: 0.5px solid var(--border);
            flex-shrink: 0;
        }

        .card-title {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted2);
            font-family: var(--mono);
        }

        .card-badge {
            font-size: 10px;
            font-family: var(--mono);
            padding: 2px 8px;
            border-radius: 999px;
            background: rgba(56, 189, 248, 0.08);
            border: 0.5px solid rgba(56, 189, 248, 0.2);
            color: var(--accent);
        }

        .card-badge.green {
            background: rgba(110, 231, 183, 0.08);
            border-color: rgba(110, 231, 183, 0.2);
            color: var(--green);
        }

        /* ── LEFT PANEL ── */
        .left-panel {
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-height: 0;
        }

        /* ── DUAL CAMERA ── */
        .cam-card {
            flex: 1;
            min-height: 0;
        }

        .dual-cam {
            display: grid;
            grid-template-columns: 1fr 1fr;
            flex: 1;
            min-height: 0;
        }

        .cam-pane {
            position: relative;
            background: #000;
            overflow: hidden;
        }

        .cam-pane:first-child {
            border-right: 0.5px solid var(--border);
        }

        .cam-pane img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .cam-overlay {
            position: absolute;
            inset: 0;
            pointer-events: none;
        }

        .cam-overlay::before,
        .cam-overlay::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            border-color: var(--accent);
            border-style: solid;
            opacity: .7;
        }

        .cam-overlay::before {
            top: 10px;
            left: 10px;
            border-width: 1.5px 0 0 1.5px;
        }

        .cam-overlay::after {
            bottom: 10px;
            right: 10px;
            border-width: 0 1.5px 1.5px 0;
        }

        .cam-rec {
            position: absolute;
            top: 8px;
            right: 8px;
            display: flex;
            align-items: center;
            gap: 4px;
            background: rgba(0, 0, 0, .6);
            backdrop-filter: blur(4px);
            border-radius: 999px;
            padding: 3px 8px;
            font-size: 9px;
            font-family: var(--mono);
            color: var(--red);
            font-weight: 600;
            letter-spacing: .4px;
        }

        .rec-dot {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: var(--red);
            animation: pdot 1s infinite;
        }

        .cam-label {
            position: absolute;
            bottom: 8px;
            left: 8px;
            font-size: 9px;
            font-family: var(--mono);
            color: rgba(255, 255, 255, .65);
            background: rgba(0, 0, 0, .5);
            padding: 2px 7px;
            border-radius: 4px;
        }

        /* ── CAPTURE ROW ── */
        .capture-card {
            flex-shrink: 0;
        }

        .capture-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .cap-pane {
            padding: 10px 14px;
        }

        .cap-pane:first-child {
            border-right: 0.5px solid var(--border);
        }

        .cap-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted);
            font-family: var(--mono);
            margin-bottom: 6px;
        }

        .cap-img-wrap {
            background: var(--bg-card2);
            border: 0.5px solid var(--border);
            border-radius: var(--rs);
            overflow: hidden;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .cap-img-wrap img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .cap-empty {
            font-size: 10px;
            color: var(--muted);
            font-family: var(--mono);
        }

        /* ── RIGHT PANEL ── */
        .right-panel {
            display: flex;
            flex-direction: column;
            gap: 10px;
            min-height: 0;
        }

        /* ── MAP ── */
        .map-card {
            flex-shrink: 0;
        }

        #map {
            height: 200px;
            width: 100%;
        }

        /* ── TELEMETRY ── */
        .tele-card {
            flex-shrink: 0;
        }

        .tele-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px;
            padding: 10px 12px;
        }

        .tele-field {
            background: var(--bg-card2);
            border: 0.5px solid var(--border);
            border-radius: var(--rs);
            padding: 8px 10px;
        }

        .tele-lbl {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .7px;
            color: var(--muted);
            font-family: var(--mono);
            margin-bottom: 4px;
        }

        .tele-val {
            font-size: 13px;
            font-weight: 600;
            font-family: var(--mono);
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* ── PROGRESS ── */
        .prog-card {
            flex: 1;
            min-height: 0;
        }

        .prog-inner {
            padding: 10px 16px;
            overflow-y: auto;
            flex: 1;
        }

        .timeline {
            position: relative;
            padding-left: 16px;
            border-left: 1.5px solid var(--border2);
        }

        .log-item {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 14px;
        }

        .log-item:last-child {
            margin-bottom: 0;
        }

        .log-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--border2);
            position: absolute;
            left: -20px;
            top: 50%;
            transform: translateY(-50%);
            transition: all .3s;
        }

        .log-item.active .log-dot {
            background: #22c55e;
            animation: pglow 1.6s infinite;
        }

        .log-item.done .log-dot {
            background: var(--accent);
        }

        @keyframes pglow {
            0% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, .5);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(34, 197, 94, 0);
            }
        }

        .log-text {
            font-size: 11px;
            color: var(--muted2);
            font-family: var(--mono);
            transition: color .3s;
        }

        .log-item.active .log-text {
            color: #22c55e;
            font-weight: 600;
        }

        .log-item.done .log-text {
            color: var(--muted);
        }

        /* ── FOOTER ── */
        footer {
            position: relative;
            z-index: 10;
            flex-shrink: 0;
            height: 26px;
            background: var(--bg-card);
            border-top: 0.5px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 16px;
            font-size: 10px;
            color: var(--muted);
            font-family: var(--mono);
        }

        #f-time {
            color: var(--muted2);
        }
    </style>
</head>

<body>

    <!-- ── HEADER ── -->
    <header>
        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M12 2C8 2 4.5 5.5 4.5 10c0 5.25 7.5 12 7.5 12s7.5-6.75 7.5-12C19.5 5.5 16 2 12 2zm0 10.5a2.5 2.5 0 1 1 0-5 2.5 2.5 0 0 1 0 5z" />
                </svg>
            </div>
            <div>
                <div class="brand-name">Arunavastra — MarineHyProjects</div>
                <div class="brand-sub">Realtime · Dual Camera + GPS Telemetry</div>
            </div>
        </div>

        <div class="header-right">
            <div class="pill pill-conn" id="conn-pill">
                <div class="dot-pulse"></div>
                <span id="conn-text">Connecting...</span>
            </div>
            <div class="pill pill-live">
                <div class="dot-pulse"></div>
                LIVE
            </div>
        </div>
    </header>

    <!-- ── STAT ROW ── -->
    <div class="stat-row">
        <div class="stat-cell sc-blue">
            <div class="stat-label">Latitude</div>
            <div class="stat-val" id="s-lat">—</div>
        </div>
        <div class="stat-cell sc-green">
            <div class="stat-label">Longitude</div>
            <div class="stat-val" id="s-lng">—</div>
        </div>
        <div class="stat-cell sc-pink">
            <div class="stat-label">SOG</div>
            <div class="stat-val" id="s-sog">—<span>kn</span></div>
        </div>
        <div class="stat-cell sc-amber">
            <div class="stat-label">COG</div>
            <div class="stat-val" id="s-cog">—<span>°</span></div>
        </div>
        <div class="stat-cell sc-teal">
            <div class="stat-label">Power</div>
            <div class="stat-val" id="s-power">—<span>%</span></div>
        </div>
        <div class="stat-cell sc-red">
            <div class="stat-label">Heading</div>
            <div class="stat-val" id="s-heading">—<span>°</span></div>
        </div>
    </div>

    <!-- ── MAIN ── -->
    <div class="main">

        <!-- LEFT -->
        <div class="left-panel">

            <!-- CAMERA CARD -->
            <div class="card cam-card">
                <div class="card-hd">
                    <div class="card-title">Interface Camera</div>
                    <div class="card-badge">socket.io · base64</div>
                </div>
                <div class="dual-cam" style="flex:1;min-height:0;">
                    <div class="cam-pane">
                        <img id="cam-underwater"
                            src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                            alt="Underwater camera">
                        <div class="cam-overlay"></div>
                        <div class="cam-rec">
                            <div class="rec-dot"></div>REC
                        </div>
                        <div class="cam-label">Underwater Imaging</div>
                    </div>
                    <div class="cam-pane">
                        <img id="cam-surface"
                            src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
                            alt="Surface camera">
                        <div class="cam-overlay"></div>
                        <div class="cam-rec">
                            <div class="rec-dot"></div>REC
                        </div>
                        <div class="cam-label">Surface Imaging</div>
                    </div>
                </div>
            </div>

            <!-- CAPTURE CARD -->
            <div class="card capture-card">
                <div class="card-hd">
                    <div class="card-title">Object Detection Capture</div>
                    <div class="card-badge green" id="cap-time">—</div>
                </div>
                <div class="capture-row">
                    <div class="cap-pane">
                        <div class="cap-label">Green Box</div>
                        <div class="cap-img-wrap" id="green-wrap">
                            <span class="cap-empty">No capture</span>
                        </div>
                    </div>
                    <div class="cap-pane">
                        <div class="cap-label">Blue Box</div>
                        <div class="cap-img-wrap" id="blue-wrap">
                            <span class="cap-empty">No capture</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="right-panel">

            <!-- MAP CARD -->
            <div class="card map-card">
                <div class="card-hd">
                    <div class="card-title">Realtime Map</div>
                    <div class="card-badge green" id="map-badge">Menunggu GPS...</div>
                </div>
                <div id="map"></div>
            </div>

            <!-- TELEMETRY CARD -->
            <div class="card tele-card">
                <div class="card-hd">
                    <div class="card-title">Telemetry</div>
                    <div class="card-badge" id="tele-time">Time: —</div>
                </div>
                <div class="tele-grid">
                    <div class="tele-field">
                        <div class="tele-lbl">Latitude</div>
                        <div class="tele-val" id="t-lat">—</div>
                    </div>
                    <div class="tele-field">
                        <div class="tele-lbl">Longitude</div>
                        <div class="tele-val" id="t-lng">—</div>
                    </div>
                    <div class="tele-field">
                        <div class="tele-lbl">SOG</div>
                        <div class="tele-val" id="t-sog">—</div>
                    </div>
                    <div class="tele-field">
                        <div class="tele-lbl">COG</div>
                        <div class="tele-val" id="t-cog">—</div>
                    </div>
                    <div class="tele-field">
                        <div class="tele-lbl">Power</div>
                        <div class="tele-val" id="t-power">—</div>
                    </div>
                    <div class="tele-field">
                        <div class="tele-lbl">Heading</div>
                        <div class="tele-val" id="t-heading">—</div>
                    </div>
                </div>
            </div>

            <!-- PROGRESS CARD -->
            <div class="card prog-card">
                <div class="card-hd">
                    <div class="card-title">Progress Position</div>
                    <div class="card-badge" id="prog-badge">Preparation</div>
                </div>
                <div class="prog-inner">
                    <div class="timeline">
                        <div class="log-item active" id="step-prep">
                            <div class="log-dot"></div>
                            <span class="log-text">Preparation</span>
                        </div>
                        <div class="log-item" id="step-start">
                            <div class="log-dot"></div>
                            <span class="log-text">Start</span>
                        </div>
                        <div class="log-item" id="step-float">
                            <div class="log-dot"></div>
                            <span class="log-text">Floating Ball 1–10</span>
                        </div>
                        <div class="log-item" id="step-surface">
                            <div class="log-dot"></div>
                            <span class="log-text">Mission Surface Imaging</span>
                        </div>
                        <div class="log-item" id="step-underwater">
                            <div class="log-dot"></div>
                            <span class="log-text">Mission Underwater Imaging</span>
                        </div>
                        <div class="log-item" id="step-finish">
                            <div class="log-dot"></div>
                            <span class="log-text">Finish</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ── FOOTER ── -->
    <footer>
        <span>Arunavastra Dashboard — Realtime Telemetry &amp; GPS Tracking</span>
        <span id="f-time">—</span>
    </footer>

    <script>
        /* ── CLOCK ── */
        function updateClock() {
            const now = new Date();
            const p = n => String(n).padStart(2, '0');
            const ts = `${p(now.getHours())}:${p(now.getMinutes())}:${p(now.getSeconds())}`;
            document.getElementById('f-time').textContent = ts;
            document.getElementById('cap-time').textContent = ts;
        }
        setInterval(updateClock, 1000);
        updateClock();

        /* ── MAP (Leaflet) ── */
        const map = L.map('map', {
                zoomControl: true,
                attributionControl: false
            })
            .setView([-6.2, 106.8], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19
        }).addTo(map);

        const marker = L.marker([-6.2, 106.8]).addTo(map);

        const trackLine = L.polyline([], {
            color: '#38bdf8',
            weight: 2.5,
            opacity: .8
        }).addTo(map);

        let coords = [];

        /* ── PROGRESS ── */
        const STEPS = ['prep', 'start', 'float', 'surface', 'underwater', 'finish'];
        let currentStep = 'prep';
        let lastLat = null,
            lastLng = null;

        function setStep(id) {
            const idx = STEPS.indexOf(id);
            STEPS.forEach((s, i) => {
                const el = document.getElementById('step-' + s);
                el.classList.remove('active', 'done');
                if (i < idx) el.classList.add('done');
                else if (i === idx) el.classList.add('active');
            });
            currentStep = id;
            document.getElementById('prog-badge').textContent =
                document.querySelector('#step-' + id + ' .log-text').textContent;
        }

        /* helper: format number */
        const fmt = (v, d = 6) => (v != null && v !== '') ? parseFloat(v).toFixed(d) : '—';

        /* ── SOCKET.IO ── */
        const socket = io();
        const connPill = document.getElementById('conn-pill');
        const connText = document.getElementById('conn-text');

        socket.on('connect', () => {
            connPill.classList.remove('err');
            connPill.classList.add('ok');
            connText.textContent = 'Connected';
        });

        socket.on('disconnect', () => {
            connPill.classList.remove('ok');
            connPill.classList.add('err');
            connText.textContent = 'Disconnected';
        });

        /* camera streams */
        socket.on('camera_underwater', data => {
            document.getElementById('cam-underwater').src = 'data:image/jpeg;base64,' + data;
        });

        socket.on('camera_surface', data => {
            document.getElementById('cam-surface').src = 'data:image/jpeg;base64,' + data;
        });

        /* telemetry */
        socket.on('real-time-update', data => {

            if (data.position) {
                const {
                    lat,
                    lng
                } = data.position;

                /* stat bar */
                document.getElementById('s-lat').innerHTML = fmt(lat, 5);
                document.getElementById('s-lng').innerHTML = fmt(lng, 5);
                document.getElementById('s-sog').innerHTML = (data.sog ?? '—') + '<span>kn</span>';
                document.getElementById('s-cog').innerHTML = (data.cog ?? '—') + '<span>°</span>';
                document.getElementById('s-power').innerHTML = (data.power ?? '—') + '<span>%</span>';
                document.getElementById('s-heading').innerHTML = (data.heading ?? '—') + '<span>°</span>';

                /* telemetry detail */
                document.getElementById('t-lat').textContent = fmt(lat);
                document.getElementById('t-lng').textContent = fmt(lng);
                document.getElementById('t-sog').textContent = (data.sog ?? '—') + ' kn';
                document.getElementById('t-cog').textContent = (data.cog ?? '—') + '°';
                document.getElementById('t-power').textContent = (data.power != null ? data.power + '%' : '—');
                document.getElementById('t-heading').textContent = (data.heading ?? '—') + '°';

                /* map badge */
                document.getElementById('map-badge').textContent =
                    `${fmt(lat,4)}, ${fmt(lng,4)}`;

                /* leaflet map */
                marker.setLatLng([lat, lng]);
                coords.push([lat, lng]);
                trackLine.setLatLngs(coords);
                map.panTo([lat, lng]);

                /* auto-progress */
                if (lastLat !== null && currentStep === 'prep') {
                    if (Math.abs(lat - lastLat) > 0.00001 || Math.abs(lng - lastLng) > 0.00001) {
                        setStep('start');
                    }
                }

                lastLat = lat;
                lastLng = lng;
            }

            if (data.geotime) {
                document.getElementById('tele-time').textContent = 'Time: ' + data.geotime;
            }
        });

        /* time update (separate event) */
        socket.on('time-update', data => {
            if (data.geotime) {
                document.getElementById('tele-time').textContent = 'Time: ' + data.geotime;
            }
        });

        /* detection captures */
        socket.on('capture-green', img => {
            const wrap = document.getElementById('green-wrap');
            wrap.innerHTML = '';
            const el = document.createElement('img');
            el.src = 'data:image/jpeg;base64,' + img;
            el.alt = 'Green box detection';
            wrap.appendChild(el);
        });

        socket.on('capture-blue', img => {
            const wrap = document.getElementById('blue-wrap');
            wrap.innerHTML = '';
            const el = document.createElement('img');
            el.src = 'data:image/jpeg;base64,' + img;
            el.alt = 'Blue box detection';
            wrap.appendChild(el);
        });

        /* expose setStep to server if needed via custom event */
        socket.on('mission-step', data => {
            if (data.step && STEPS.includes(data.step)) setStep(data.step);
        });
    </script>

</body>

</html>
