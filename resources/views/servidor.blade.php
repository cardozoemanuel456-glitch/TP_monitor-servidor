<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Monitor de Servidor Avanzado</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-gradient: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            --card-bg: rgba(30, 41, 59, 0.7);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --accent-glow: #6366f1;
            --success: #10b981;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg-gradient);
            color: var(--text-main);
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            padding: 20px 30px;
            border-radius: 16px;
            margin-bottom: 30px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: var(--success);
            border-radius: 50%;
            box-shadow: 0 0 10px var(--success);
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.8; }
            50% { transform: scale(1.2); opacity: 1; box-shadow: 0 0 15px var(--success); }
            100% { transform: scale(0.95); opacity: 0.8; }
        }

        .last-update {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .card {
            background: var(--card-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--card-border);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 20px -4px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 3px;
            background: linear-gradient(90deg, #6366f1, #a855f7);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 12px 30px -4px rgba(99, 102, 241, 0.15);
        }

        .card:hover::before {
            opacity: 1;
        }

        .label {
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .value.small {
            font-size: 16px;
            font-weight: 600;
            word-break: break-all;
            color: #cbd5e1;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header">
        <h1>
             Monitor del Servidor
        </h1>
        <div class="live-indicator">
            <div class="pulse-dot"></div>
            En vivo (Actualización cada 5s)
        </div>
    </div>

    <div class="last-update">
        Última sincronización: <span id="at" style="color: #f8fafc; font-weight: 600;">{{ $data['at'] }}</span>
    </div>

    <div class="grid">
        <div class="card">
            <div class="label">Nombre del equipo</div>
            <div class="value small">{{ $data['hostname'] }}</div>
        </div>
        
        <div class="card">
            <div class="label">Sistema operativo</div>
            <div class="value small">{{ $data['os'] }}</div>
        </div>
        
        <div class="card">
            <div class="label">Versión de PHP</div>
            <div class="value" style="color: #818cf8;">{{ $data['php'] }}</div>
        </div>
        
        <div class="card">
            <div class="label">Disco usado (%)</div>
            <div class="value" id="disk">{{ $data['disk']['used_pct'] }}%</div>
        </div>
        
        <div class="card">
            <div class="label">Memoria usada (%)</div>
            <div class="value" id="memory">{{ isset($data['memory']['used_pct']) ? $data['memory']['used_pct'] . '%' : 'No disponible' }}</div>
        </div>
        
        <div class="card">
            <div class="label">Uso de CPU (%)</div>
            <div class="value" id="cpu">{{ isset($data['cpu_pct']) ? $data['cpu_pct'] . '%' : 'No disponible' }}</div>
        </div>
        
        <div class="card">
            <div class="label">Temperatura CPU</div>
            <div class="value" id="temp" style="color: #38bdf8;">{{ isset($data['cpu_temp_c']) ? $data['cpu_temp_c'] . '°C' : 'No disponible' }}</div>
        </div>
        
        <div class="card">
            <div class="label">Tiempo encendido</div>
            <div class="value" id="uptime" style="font-size: 20px; font-weight: 600;">{{ $data['uptime_s'] ?? 'No disponible' }}</div>
        </div>
    </div>
</div>

<script>
async function actualizar() {
    try {
        const res = await fetch('/api/metrics');
        const d = await res.json();
        
        document.getElementById('at').textContent = d.at;
        document.getElementById('disk').textContent = d.disk.used_pct + '%';
        document.getElementById('memory').textContent = (d.memory?.used_pct !== null && d.memory?.used_pct !== undefined) ? d.memory.used_pct + '%' : 'No disponible';
        document.getElementById('cpu').textContent = (d.cpu_pct !== null && d.cpu_pct !== undefined) ? d.cpu_pct + '%' : 'No disponible';
        document.getElementById('temp').textContent = (d.cpu_temp_c !== null && d.cpu_temp_c !== undefined) ? d.cpu_temp_c + '°C' : 'No disponible';
        document.getElementById('uptime').textContent = d.uptime_s ?? 'No disponible';
    } catch (e) {
        console.error("Error al actualizar las métricas", e);
    }
}
setInterval(actualizar, 5000);
</script>

</body>
</html>