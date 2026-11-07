<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2D Space Game - USS Enterprise</title>
    <style>
        body {
            background-color: #0b0f19;
            color: #00ffcc;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            font-family: monospace;
        }

        .game-container {
            position: relative;
            width: 800px;
            height: 600px;
            border: 4px solid #1f293d;
            box-shadow: 0 0 20px rgba(0, 255, 204, 0.15);
        }

        canvas {
            display: block;
            background-color: #030508;
        }

        /* HTML Overlay HUD */
        #hud-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none; /* Let clicks pass through to canvas */
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 15px;
            box-sizing: border-box;
        }

        .hud-panel {
            background: rgba(3, 5, 8, 0.75);
            border: 1px solid #1f293d;
            padding: 8px 14px;
            font-size: 13px;
            letter-spacing: 1px;
            display: flex;
            gap: 20px;
        }

        .controls-hint {
            margin-top: 10px;
            color: #6b7280;
            font-size: 12px;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="game-container">
        <canvas id="gameCanvas" width="800" height="600"></canvas>
        
        <!-- HUD Overlay Layer -->
        <div id="hud-overlay">
            <div class="hud-panel">
                <div>VESSEL: <span style="color: #fff;">USS ENTERPRISE (NCC-1701)</span></div>
                <div>X: <span id="hud-x">100</span></div>
                <div>Y: <span id="hud-y">100</span></div>
                <div>VELOCITY: <span id="hud-speed">0.0</span></div>
            </div>
            <div class="hud-panel" style="font-size: 11px; color: #4b5563;">
                STATUS: DEEP SPACE EXPLORATION
            </div>
        </div>
    </div>
    
    <div class="controls-hint">Use <b>W / Up Arrow</b> to Impulse Thrust | <b>A/D or Left/Right Arrows</b> to Rotate Heading</div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        // World & Camera Settings
        const WORLD_WIDTH = 2400;
        const WORLD_HEIGHT = 1800;

        // Player Spaceship (USS Enterprise Vector Style)
        const player = {
            x: WORLD_WIDTH / 2,
            y: WORLD_HEIGHT / 2,
            vx: 0,
            vy: 0,
            speed: 0.15,      // Acceleration rate
            friction: 0.98,   // Space drag
            maxSpeed: 5.0,
            angle: -Math.PI / 2 // Facing upward initially
        };

        // Static Celestial Bodies & Stations
        const planets = [
            { x: 400, y: 300, radius: 70, color: '#1e3a8a', ringColor: '#3b82f6', name: "Alpha Centauri IV" },
            { x: 1900, y: 1200, radius: 110, color: '#7c2d12', ringColor: null, name: "Vulcan Prime" }
        ];

        const stations = [
            { x: 1400, y: 500, radius: 45, rotation: 0 }
        ];

        // Background Starfield (Generated once)
        const stars = [];
        for (let i = 0; i < 200; i++) {
            stars.push({
                x: Math.random() * WORLD_WIDTH,
                y: Math.random() * WORLD_HEIGHT,
                size: Math.random() * 1.8,
                brightness: Math.random()
            });
        }

        // Input state tracking
        const keys = {};
        window.addEventListener('keydown', (e) => keys[e.key] = true);
        window.addEventListener('keyup', (e) => keys[e.key] = false);

        // Update Game Logic
        function update() {
            // Rotation controls
            if (keys['ArrowLeft'] || keys['a'] || keys['A']) player.angle -= 0.04;
            if (keys['ArrowRight'] || keys['d'] || keys['D']) player.angle += 0.04;

            // Thrust controls
            let accelerating = false;
            if (keys['ArrowUp'] || keys['w'] || keys['W']) {
                player.vx += Math.cos(player.angle) * player.speed;
                player.vy += Math.sin(player.angle) * player.speed;
                accelerating = true;
            }

            // Apply space friction / drag
            player.vx *= player.friction;
            player.vy *= player.friction;

            // Cap max velocity
            const currentSpeed = Math.hypot(player.vx, player.vy);
            if (currentSpeed > player.maxSpeed) {
                player.vx = (player.vx / currentSpeed) * player.maxSpeed;
                player.vy = (player.vy / currentSpeed) * player.maxSpeed;
            }

            // Update position
            player.x += player.vx;
            player.y += player.vy;

            // World boundaries (wrap around space)
            if (player.x < 0) player.x = WORLD_WIDTH;
            if (player.x > WORLD_WIDTH) player.x = 0;
            if (player.y < 0) player.y = WORLD_HEIGHT;
            if (player.y > WORLD_HEIGHT) player.y = 0;

            // Rotate space station slowly
            stations.forEach(s => s.rotation += 0.005);

            // Update DOM HUD info
            document.getElementById('hud-x').innerText = Math.round(player.x);
            document.getElementById('hud-y').innerText = Math.round(player.y);
            document.getElementById('hud-speed').innerText = currentSpeed.toFixed(1);
        }

        // Render World
        function draw() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // Camera offset (Center player on canvas)
            const cameraX = player.x - canvas.width / 2;
            const cameraY = player.y - canvas.height / 2;

            ctx.save();
            ctx.translate(-cameraX, -cameraY);

            // 1. Draw Starfield Background
            stars.forEach(star => {
                ctx.fillStyle = `rgba(255, 255, 255, ${0.3 + star.brightness * 0.7})`;
                ctx.fillRect(star.x, star.y, star.size, star.size);
            });

            // 2. Draw Planets
            planets.forEach(p => {
                // Planet body
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.strokeStyle = '#ffffff22';
                ctx.lineWidth = 2;
                ctx.stroke();

                // Optional planetary rings
                if (p.ringColor) {
                    ctx.strokeStyle = p.ringColor;
                    ctx.lineWidth = 6;
                    ctx.beginPath();
                    ctx.ellipse(p.x, p.y, p.radius * 1.8, p.radius * 0.5, Math.PI / 6, 0, Math.PI * 2);
                    ctx.stroke();
                }

                // Planet Label
                ctx.fillStyle = '#9ca3af';
                ctx.font = '11px monospace';
                ctx.textAlign = 'center';
                ctx.fillText(p.name, p.x, p.y + p.radius + 18);
            });

            // 3. Draw Space Station
            stations.forEach(s => {
                ctx.save();
                ctx.translate(s.x, s.y);
                ctx.rotate(s.rotation);

                // Station ring structure
                ctx.strokeStyle = '#94a3b8';
                ctx.lineWidth = 4;
                ctx.beginPath();
                ctx.arc(0, 0, s.radius, 0, Math.PI * 2);
                ctx.stroke();

                // Spokes & core
                ctx.fillStyle = '#cbd5e1';
                ctx.fillRect(-6, -6, 12, 12);
                ctx.strokeStyle = '#64748b';
                ctx.lineWidth = 2;
                ctx.beginPath();
                ctx.moveTo(-s.radius, 0); ctx.lineTo(s.radius, 0);
                ctx.moveTo(0, -s.radius); ctx.lineTo(0, s.radius);
                ctx.stroke();

                ctx.restore();
            });

            // 4. Draw USS Enterprise Player Ship
            ctx.save();
            ctx.translate(player.x, player.y);
            ctx.rotate(player.angle); // Heading direction

            // Enterprise Scale Configuration
            // Drawing points relative to center origin pointing right (0 radians)
            
            // A. Warp Nacelle Pylons (Angled struts sweeping down/out)
            ctx.strokeStyle = '#94a3b8';
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.moveTo(-4, 0); ctx.lineTo(-12, -16);
            ctx.moveTo(-4, 0); ctx.lineTo(-12, 16);
            ctx.stroke();

            // B. Twin Warp Nacelles (Long red/blue cylinders at the rear)
            ctx.fillStyle = '#cbd5e1';
            // Port Nacelle
            ctx.fillRect(-22, -22, 34, 6);
            ctx.fillStyle = '#ef4444'; // Bussard collector red glow front tip
            ctx.fillRect(-22, -22, 6, 6);
            
            // Starboard Nacelle
            ctx.fillStyle = '#cbd5e1';
            ctx.fillRect(-22, 16, 34, 6);
            ctx.fillStyle = '#ef4444';
            ctx.fillRect(-22, 16, 6, 6);

            // C. Secondary Hull (Engineering Body - cylindrical main spine)
            ctx.fillStyle = '#e2e8f0';
            ctx.beginPath();
            ctx.roundRect(-24, -5, 32, 10, [4]);
            ctx.fill();

            // D. Connecting Neck (Bridge/Saucer to Engineering Hull)
            ctx.fillStyle = '#cbd5e1';
            ctx.fillRect(-2, -3, 10, 6);

            // E. Primary Hull (The Iconic Saucer Section - circular dish up front)
            ctx.fillStyle = '#f8fafc';
            ctx.beginPath();
            ctx.ellipse(8, 0, 16, 22, 0, 0, Math.PI * 2);
            ctx.fill();
            ctx.strokeStyle = '#64748b';
            ctx.lineWidth = 1;
            ctx.stroke();

            // Saucer bridge superstructure accent dot
            ctx.fillStyle = '#38bdf8';
            ctx.beginPath();
            ctx.arc(10, 0, 3, 0, Math.PI * 2);
            ctx.fill();

            // F. Impulse Engine Glow (Rear engine exhaust when moving)
            if (keys['ArrowUp'] || keys['w'] || keys['W'] || Math.hypot(player.vx, player.vy) > 0.5) {
                ctx.fillStyle = '#38bdf8'; // Ion blue glow
                ctx.shadowColor = '#00ffcc';
                ctx.shadowBlur = 10;
                ctx.fillRect(-27, -3, 5, 6);
                ctx.shadowBlur = 0; // Reset shadow
            }

            ctx.restore();

            ctx.restore(); // Restore camera translation
        }

        // Main Loop
        function gameLoop() {
            update();
            draw();
            requestAnimationFrame(gameLoop);
        }

        requestAnimationFrame(gameLoop);
    </script>
</body>
</html>