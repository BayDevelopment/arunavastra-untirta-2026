<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Monitoring Raspberry Pi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        .container {
            padding: 24px;
        }

        .header {
            margin-bottom: 24px;
        }

        .grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        }

        .camera-stream {
            width: 100%;
            border-radius: 12px;
            background: #000;
        }

        .gps-item {
            margin-bottom: 12px;
            font-size: 15px;
        }

        .label {
            color: #6b7280;
            font-size: 13px;
        }

        .value {
            font-weight: bold;
            font-size: 18px;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            background: #dcfce7;
            color: #166534;
            font-size: 13px;
            font-weight: bold;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: 0;
            border-radius: 12px;
            margin-top: 12px;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>Dashboard Monitoring Raspberry Pi</h1>
        <p>Realtime webcam dan GPS NEO M8N</p>
    </div>

    <div class="grid">
        <div class="card">
            <h2>Kamera Realtime</h2>

            <img
                class="camera-stream"
                src="http://{{ $raspberryIp }}:8080/?action=stream"
                alt="Webcam Stream"
            >
        </div>

        <div class="card">
            <h2>Data GPS</h2>

            <p>
                <span id="gps-status" class="status">Mengambil data...</span>
            </p>

            <div class="gps-item">
                <div class="label">Latitude</div>
                <div id="latitude" class="value">-</div>
            </div>

            <div class="gps-item">
                <div class="label">Longitude</div>
                <div id="longitude" class="value">-</div>
            </div>

            <div class="gps-item">
                <div class="label">Altitude</div>
                <div id="altitude" class="value">-</div>
            </div>

            <div class="gps-item">
                <div class="label">Speed</div>
                <div id="speed" class="value">-</div>
            </div>

            <iframe id="map" src=""></iframe>
        </div>
    </div>
</div>

<script>
    const raspberryIp = "{{ $raspberryIp }}";
    const gpsUrl = `http://${raspberryIp}:5000/api/gps`;

    async function loadGps() {
        try {
            const response = await fetch(gpsUrl);
            const data = await response.json();

            const status = document.getElementById('gps-status');

            if (data.status !== 'ok') {
                status.innerText = data.message || 'GPS belum fix';
                status.style.background = '#fee2e2';
                status.style.color = '#991b1b';
                return;
            }

            status.innerText = 'GPS aktif';
            status.style.background = '#dcfce7';
            status.style.color = '#166534';

            document.getElementById('latitude').innerText = data.latitude;
            document.getElementById('longitude').innerText = data.longitude;
            document.getElementById('altitude').innerText = `${data.altitude ?? '-'} m`;
            document.getElementById('speed').innerText = `${data.speed ?? 0} m/s`;

            document.getElementById('map').src =
                `https://maps.google.com/maps?q=${data.latitude},${data.longitude}&z=16&output=embed`;

        } catch (error) {
            const status = document.getElementById('gps-status');
            status.innerText = 'Gagal konek ke Raspberry Pi';
            status.style.background = '#fee2e2';
            status.style.color = '#991b1b';
        }
    }

    loadGps();
    setInterval(loadGps, 3000);
</script>

</body>
</html>
