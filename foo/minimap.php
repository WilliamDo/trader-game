<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LCARS - USS Defiant (NX-74205)</title>
    <style>
        /* TNG LCARS Color Palette */
        :root {
            --lcars-orange: #ff9900;
            --lcars-peach: #ffcc99;
            --lcars-gold: #cc9933;
            --lcars-red: #cc6666;
            --lcars-blue: #9999cc;
            --lcars-dark: #000000;
            --lcars-bg: #030508;
        }

        body {
            background-color: var(--lcars-bg);
            color: var(--lcars-peach);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: 'Helvetica Neue', Arial, sans-serif;
            overflow: hidden;
            letter-spacing: 1.5px;
        }

        .game-container {
            position: relative;
            width: 800px;
            height: 600px;
            border: 4px solid var(--lcars-orange);
            box-shadow: 0 0 25px rgba(255, 153, 0, 0.25);
            background-color: #000;
        }

        #gameCanvas {
            display: block;
            background-color: #030508;
        }

        /* LCARS Overlay UI Layout */
        #lcars-hud {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Allows clicks to pass through to canvas */
            display: grid;
            grid-template-columns: 150px 1fr;
            grid-template-rows: 60px 1fr 40px;
            box-sizing: border-box;
            padding: 8px;
            gap: 6px;
        }

        /* LCARS Left Column Sidebar */
        .lcars-sidebar {
            grid-row: 1 / span 3;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .lcars-elbow-top {
            background-color: var(--lcars-orange);
            height: 45px;
            border-top-left-radius: 22px;
            border-bottom-left-radius: 22px;
            display: flex;
            align-items: center;
            padding-left: 14px;
            font-weight: bold;
            font-size: 11px;
            color: #000;
        }

        /* Minimap Container Panel */
        .lcars-minimap-panel {
            background-color: var(--lcars-gold);
            border-radius: 8px;
            padding: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .lcars-minimap-label {
            font-size: 9px;
            font-weight: bold;
            color: #000;
            text-align: center;
        }

        #minimapCanvas {
            background-color: #000;
            border: 2px solid #000;
            display: block;
        }

        .lcars-bar-peach {
            background-color: var(--lcars-peach);
            flex-grow: 1;
            border-radius: 6px;
            min-height: 40px;
        }

        .lcars-bar-red {
            background-color: var(--lcars-red);
            height: 30px;
            border-radius: 6px;
        }

        /* LCARS Top Header Bar */
        .lcars-header {
            grid-column: 2;
            background-color: var(--lcars-orange);
            border-top-right-radius: 22px;
            border-bottom-right-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            color: #000;
            font-weight: bold;
            font-size: 13px;
        }

        /* LCARS Main Readout Panel Area */
        .lcars-main-display {
            grid-column: 2;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 10px;
            pointer-events: none;
        }

        .lcars-readout-box {
            background: rgba(0, 0, 0, 0.8);
            border: 1px solid var(--lcars-orange);
            padding: 10px 14px;
            font-size: 11px;
            color: var(--lcars-peach);
            box-shadow: inset 0 0 10px rgba(255, 153, 0, 0.15);
        }

        .lcars-readout-box span {
            color: var(--lcars-orange);
            font-weight: bold;
        }

        /* LCARS Footer Bar */
        .lcars-footer {
            grid-column: 2;
            background-color: var(--lcars-blue);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            color: #000;
            font-weight: bold;
            font-size: 10px;
        }

        .controls-hint {
            margin-top: 10px;
            color: #6b7280;
            font-size: 11px;
            text-align: center;
            font-family: monospace;
        }
    </style>
</head>
<body>

    <div class="game-container">
        <!-- Main World Canvas (Background) -->
        <canvas id="gameCanvas" width="800" height="600"></canvas>
        
        <!-- TNG LCARS Overlay Interface -->
        <div id="lcars-hud">
            <!-- Left Side Data Pillar with Radar Minimap -->
            <div class="lcars-sidebar">
                <div class="lcars-elbow-top">74205</div>
                
                <!-- Minimap Panel -->
                <div class="lcars-minimap-panel">
                    <div class="lcars-minimap-label">LONG-RANGE SENSORS</div>
                    <canvas id="minimapCanvas" width="134" height="134"></canvas>
                </div>

                <div class="lcars-bar-peach"></div>
                <div class="lcars-bar-red"></div>
            </div>

            <!-- Top Header Status Line -->
            <div class="lcars-header">
                <div>USS DEFIANT [NX-74205]</div>
                <div>TACTICAL / HELM SENSORS</div>
            </div>

            <!-- Central Data Readouts -->
            <div class="lcars-main-display">
                <div class="lcars-readout-box">
                    <div>POSITION X: <span id="hud-x">1200</span></div>
                    <div>POSITION Y: <span id="hud-y">900</span></div>
                    <div>IMPULSE VEL: <span id="hud-speed">0.0</span></div>
                </div>
                <div class="lcars-readout-box" style="text-align: right;">
                    <div>SHIELDS: <span>100%</span></div>
                    <div>STRUCTURAL INT: <span>OPTIMAL</span></div>
                </div>
            </div>

            <!-- Bottom LCARS Accent Bar -->
            <div class="lcars-footer">
                <div>DATABASE SEARCH: ACTIVE</div>
                <div>LCARS v4.2</div>
            </div>
        </div>
    </div>
    
    <div class="controls-hint">Use <b>W / Up Arrow</b> to Impulse Thrust | <b>A/D or Left/Right Arrows</b> to Rotate Heading</div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        const minimapCanvas = document.getElementById('minimapCanvas');
        const minimapCtx = minimapCanvas.getContext('2d');

        // World & Camera Settings
        const WORLD_WIDTH = 2400;
        const WORLD_HEIGHT = 1800;

        // Player Spaceship
        const player = {
            x: WORLD_WIDTH / 2,
            y: WORLD_HEIGHT / 2,
            vx: 0,
            vy: 0,
            speed: 0.18,
            friction: 0.98,
            maxSpeed: 6.0,
            angle: -Math.PI / 2
        };

        // Static Celestial Bodies & Stations
        const planets = [
            { x: 400, y: 300, radius: 70, color: '#3b82f6', name: "Alpha Centauri IV" },
            { x: 1900, y: 1200, radius: 110, color: '#ef4444', name: "Vulcan Prime" }
        ];

        const stations = [
            { x: 1400, y: 500, radius: 45, rotation: 0 }
        ];

        // Background Starfield
        const stars = [];
        for (let i = 0; i < 200; i++) {
            stars.push({
                x: Math.random() * WORLD_WIDTH,
                y: Math.random() * WORLD_HEIGHT,
                size: Math.random() * 1.8,
                brightness: Math.random()
            });
        }

        const keys = {};
        window.addEventListener('keydown', (e) => keys[e.key] = true);
        window.addEventListener('keyup', (e) => keys[e.key] = false);

        function update() {
            if (keys['ArrowLeft'] || keys['a'] || keys['A']) player.angle -= 0.05;
            if (keys['ArrowRight'] || keys['d'] || keys['D']) player.angle += 0.05;

            if (keys['ArrowUp'] || keys['w'] || keys['W']) {
                player.vx += Math.cos(player.angle) * player.speed;
                player.vy += Math.sin(player.angle) * player.speed;
            }

            player.vx *= player.friction;
            player.vy *= player.friction;

            const currentSpeed = Math.hypot(player.vx, player.vy);
            if (currentSpeed > player.maxSpeed) {
                player.vx = (player.vx / currentSpeed) * player.maxSpeed;
                player.vy = (player.vy / currentSpeed) * player.maxSpeed;
            }

            player.x += player.vx;
            player.y += player.vy;

            if (player.x < 0) player.x = WORLD_WIDTH;
            if (player.x > WORLD_WIDTH) player.x = 0;
            if (player.y < 0) player.y = WORLD_HEIGHT;
            if (player.y > WORLD_HEIGHT) player.y = 0;

            stations.forEach(s => s.rotation += 0.005);

            document.getElementById('hud-x').innerText = Math.round(player.x);
            document.getElementById('hud-y').innerText = Math.round(player.y);
            document.getElementById('hud-speed').innerText = currentSpeed.toFixed(1);
        }

        // Render Minimap Radar
        function drawMinimap() {
            minimapCtx.clearRect(0, 0, minimapCanvas.width, minimapCanvas.height);

            const scaleX = minimapCanvas.width / WORLD_WIDTH;
            const scaleY = minimapCanvas.height / WORLD_HEIGHT;

            // Draw Planets on Radar
            planets.forEach(p => {
                minimapCtx.fillStyle = p.color;
                minimapCtx.beginPath();
                minimapCtx.arc(p.x * scaleX, p.y * scaleY, Math.max(2, p.radius * scaleX), 0, Math.PI * 2);
                minimapCtx.fill();
            });

            // Draw Stations on Radar
            stations.forEach(s => {
                minimapCtx.fillStyle = '#94a3b8';
                minimapCtx.fillRect((s.x - 5) * scaleX, (s.y - 5) * scaleY, 6, 6);
            });

            // Draw Player Position on Radar (Bright Amber/Gold blip)
            minimapCtx.fillStyle = '#ff9900';
            minimapCtx.beginPath();
            minimapCtx.arc(player.x * scaleX, player.y * scaleY, 3, 0, Math.PI * 2);
            minimapCtx.fill();
        }

        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const cameraX = player.x - canvas.width / 2;
            const cameraY = player.y - canvas.height / 2;

            ctx.save();
            ctx.translate(-cameraX, -cameraY);

            // Stars
            stars.forEach(star => {
                ctx.fillStyle = `rgba(255, 255, 255, ${0.3 + star.brightness * 0.7})`;
                ctx.fillRect(star.x, star.y, star.size, star.size);
            });

            // Planets
            planets.forEach(p => {
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.strokeStyle = '#ffffff22';
                ctx.lineWidth = 2;
                ctx.stroke();

                ctx.fillStyle = '#9ca3af';
                ctx.font = '11px monospace';
                ctx.textAlign = 'center';
                ctx.fillText(p.name, p.x, p.y + p.radius + 18);
            });

            // Space Station
            stations.forEach(s => {
                ctx.save();
                ctx.translate(s.x, s.y);
                ctx.rotate(s.rotation);

                ctx.strokeStyle = '#94a3b8';
                ctx.lineWidth = 4;
                ctx.beginPath();
                ctx.arc(0, 0, s.radius, 0, Math.PI * 2);
                ctx.stroke();

                ctx.fillStyle = '#cbd5e1';
                ctx.fillRect(-6, -6, 12, 12);
                ctx.restore();
            });

            // USS Defiant Player Ship
            ctx.save();
            ctx.translate(player.x, player.y);
            ctx.rotate(player.angle);

            ctx.fillStyle = '#cbd5e1';
            ctx.strokeStyle = '#475569';
            ctx.lineWidth = 1.5;

            ctx.beginPath();
            ctx.moveTo(24, 0);
            ctx.lineTo(14, -8);
            ctx.lineTo(10, -18);
            ctx.lineTo(-12, -22);
            ctx.lineTo(-20, -14);
            ctx.lineTo(-22, -6);
            ctx.lineTo(-24, 0);
            ctx.lineTo(-22, 6);
            ctx.lineTo(-20, 14);
            ctx.lineTo(-12, 22);
            ctx.lineTo(10, 18);
            ctx.lineTo(14, 8);
            ctx.closePath();
            ctx.fill();
            ctx.stroke();

            ctx.fillStyle = '#ef4444';
            ctx.fillRect(8, -19, 5, 4);
            ctx.fillRect(8, 15, 5, 4);

            ctx.fillStyle = '#38bdf8';
            ctx.fillRect(-8, -21, 14, 3);
            ctx.fillRect(-8, 18, 14, 3);

            ctx.fillStyle = '#f97316';
            ctx.beginPath();
            ctx.arc(20, 0, 3, 0, Math.PI * 2);
            ctx.fill();

            if (keys['ArrowUp'] || keys['w'] || keys['W'] || Math.hypot(player.vx, player.vy) > 0.5) {
                ctx.fillStyle = '#fb923c';
                ctx.shadowColor = '#f97316';
                ctx.shadowBlur = 8;
                ctx.fillRect(-26, -5, 4, 3);
                ctx.fillRect(-26, 2, 4, 3);
                ctx.shadowBlur = 0;
            }

            ctx.restore();
            ctx.restore();

            // Render the minimap radar overlay
            drawMinimap();
        }

        function gameLoop() {
            update();
            draw();
            requestAnimationFrame(gameLoop);
        }

        requestAnimationFrame(gameLoop);
    </script>
</body>
</html>