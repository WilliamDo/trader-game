<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>2D Space Game - USS Defiant</title>
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
                <div>VESSEL: <span style="color: #ff9900;">USS DEFIANT (NX-74205)</span></div>
                <div>X: <span id="hud-x">100</span></div>
                <div>Y: <span id="hud-y">100</span></div>
                <div>VELOCITY: <span id="hud-speed">0.0</span></div>
            </div>
            <div class="hud-panel" style="font-size: 11px; color: #4b5563;">
                STATUS: DEFANT-CLASS ESCORT PATROL
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

        // Player Spaceship (USS Defiant Vector Style)
        const player = {
            x: WORLD_WIDTH / 2,
            y: WORLD_HEIGHT / 2,
            vx: 0,
            vy: 0,
            speed: 0.18,      // Defiant is fast & nimble
            friction: 0.98,   // Space drag
            maxSpeed: 6.0,
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
            if (keys['ArrowLeft'] || keys['a'] || keys['A']) player.angle -= 0.05;
            if (keys['ArrowRight'] || keys['d'] || keys['D']) player.angle += 0.05;

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
                ctx.fillStyle = p.color;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.strokeStyle = '#ffffff22';
                ctx.lineWidth = 2;
                ctx.stroke();

                if (p.ringColor) {
                    ctx.strokeStyle = p.ringColor;
                    ctx.lineWidth = 6;
                    ctx.beginPath();
                    ctx.ellipse(p.x, p.y, p.radius * 1.8, p.radius * 0.5, Math.PI / 6, 0, Math.PI * 2);
                    ctx.stroke();
                }

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

                ctx.strokeStyle = '#94a3b8';
                ctx.lineWidth = 4;
                ctx.beginPath();
                ctx.arc(0, 0, s.radius, 0, Math.PI * 2);
                ctx.stroke();

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

            // 4. Draw USS Defiant (NX-74205 Escort Class)
            ctx.save();
            ctx.translate(player.x, player.y);
            ctx.rotate(player.angle); // Heading direction

            // A. Main Armored Wedge Hull Body
            ctx.fillStyle = '#cbd5e1';
            ctx.strokeStyle = '#475569';
            ctx.lineWidth = 1.5;

            ctx.beginPath();
            ctx.moveTo(24, 0);          // Forward nose (Defiant forward pod)
            ctx.lineTo(14, -8);         // Port bow transition
            ctx.lineTo(10, -18);        // Port wingtip forward edge (integrated nacelle casing)
            ctx.lineTo(-12, -22);       // Port nacelle outer edge
            ctx.lineTo(-20, -14);       // Port aft flank
            ctx.lineTo(-22, -6);        // Port engine notch
            ctx.lineTo(-24, 0);         // Aft center stern
            ctx.lineTo(-22, 6);         // Starboard engine notch
            ctx.lineTo(-20, 14);        // Starboard aft flank
            ctx.lineTo(-12, 22);        // Starboard nacelle outer edge
            ctx.lineTo(10, 18);         // Starboard wingtip forward edge
            ctx.lineTo(14, 8);          // Starboard bow transition
            ctx.closePath();
            ctx.fill();
            ctx.stroke();

            // B. Integrated Nacelles (Bussard Collectors & Plasma Grids)
            // Port Bussard Ramscoop (Forward Red Glow)
            ctx.fillStyle = '#ef4444';
            ctx.fillRect(8, -19, 5, 4);
            // Starboard Bussard Ramscoop
            ctx.fillRect(8, 15, 5, 4);

            // Warp Nacelle Blue Glow Channels (Midbody inset)
            ctx.fillStyle = '#38bdf8';
            ctx.fillRect(-8, -21, 14, 3);
            ctx.fillRect(-8, 18, 14, 3);

            // C. Forward Deflector Array / Nose Pod Accent (Copper/Orange Glow)
            ctx.fillStyle = '#f97316';
            ctx.beginPath();
            ctx.arc(20, 0, 3, 0, Math.PI * 2);
            ctx.fill();

            // D. Command Deck Structure & Pulse Phaser Cannons
            // Central Command Deck Outer Rim
            ctx.fillStyle = '#94a3b8';
            ctx.beginPath();
            ctx.ellipse(-2, 0, 12, 7, 0, 0, Math.PI * 2);
            ctx.fill();
            ctx.stroke();

            // Bridge Dome Accent
            ctx.fillStyle = '#e2e8f0';
            ctx.beginPath();
            ctx.arc(-2, 0, 3.5, 0, Math.PI * 2);
            ctx.fill();

            // Pulse Phaser Cannon Mounts (Wing Roots)
            ctx.fillStyle = '#475569';
            ctx.fillRect(11, -12, 6, 2.5); // Port cannon
            ctx.fillRect(11, 9.5, 6, 2.5);  // Starboard cannon

            // E. Dual Impulse Engine Glow (Aft Thrusters)
            if (keys['ArrowUp'] || keys['w'] || keys['W'] || Math.hypot(player.vx, player.vy) > 0.5) {
                ctx.fillStyle = '#fb923c'; // Warm orange impulse glow
                ctx.shadowColor = '#f97316';
                ctx.shadowBlur = 8;
                // Dual aft impulse ports
                ctx.fillRect(-26, -5, 4, 3);
                ctx.fillRect(-26, 2, 4, 3);
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