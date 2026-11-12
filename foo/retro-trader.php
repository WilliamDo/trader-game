<html>
<head>
  <title>Retro Trader Game</title>
  <style>
    canvas {
      background: #0b0b12;
    }
  </style>
</head>
<body>

  <h1>Welcome to Retro Trader!</h1>

  <canvas id="map" width="480" height="480"></canvas>

  <script>
    const canvas = document.getElementById('map');
    const ctx = canvas.getContext('2d');

    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;

    /**
     * Draws a spaceship pointing UP.
     * @param {number} offsetX - X displacement relative to the center of the canvas
     * @param {number} offsetY - Y displacement relative to the center of the canvas
     * @param {string} hullColor - Color of the ship's body
     */
    function drawShip(offsetX = 0, offsetY = 0, hullColor = '#38bdf8') {
      ctx.save();

      // Translate origin to canvas center + relative offset
      ctx.translate(centerX + offsetX, centerY + offsetY);

      // --- SHIP GEOMETRY (Pointed Upwards) ---
      // (0,0) is now the center of the ship
      // Negative Y is UP, Positive Y is DOWN

      // 1. Draw Hull Body
      ctx.fillStyle = hullColor;
      ctx.beginPath();
      ctx.moveTo(0, -16);   // Nose cone (pointing up)
      ctx.lineTo(-10, 12);  // Left wing tip
      ctx.lineTo(0, 6);     // Rear engine notch
      ctx.lineTo(10, 12);   // Right wing tip
      ctx.closePath();
      ctx.fill();

      // 2. Draw Engine Flame Glow
      ctx.fillStyle = '#f43f5e';
      ctx.fillRect(-3, 8, 6, 5);

      ctx.restore();
    }

    // --- DRAW DEMO SHIPS ---
    // Clear screen
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Ship 1: Dead center (0, 0 relative to center)
    drawShip(0, 0, '#38bdf8');

    // Game State
    const state = {
      ship: {
        x: 0,          // Current X relative to center
        y: 0,          // Current Y relative to center
        speed: 2.5,    // Pixels moved per frame
        angle: 0       // Rotation heading
      },
      target: {
        x: 0,          // Target X relative to center
        y: 0,          // Target Y relative to center
        active: false  // Is it currently moving?
      }
    };

    // 1. CAPTURE CLICK & SET TARGET
    canvas.addEventListener('click', (e) => {
      const rect = canvas.getBoundingClientRect();
      const mouseX = e.clientX - rect.left;
      const mouseY = e.clientY - rect.top;

      // Convert canvas-local coordinates to center-relative coordinates
      state.target.x = mouseX - centerX;
      state.target.y = mouseY - centerY;
      state.target.active = true;
    });

    // 2. UPDATE LOGIC LOOP (Moves ship closer each frame)
    function update() {
      if (!state.target.active) return;

      // Calculate distance components
      const dx = state.target.x - state.ship.x;
      const dy = state.target.y - state.ship.y;
      const distance = Math.hypot(dx, dy);

      // If close enough to target, snap to it and stop
      if (distance < state.ship.speed) {
        state.ship.x = state.target.x;
        state.ship.y = state.target.y;
        state.target.active = false;
        return;
      }

      // Calculate angle towards the target
      // Note: + Math.PI / 2 adjusts standard angle because our ship art points UP
      state.ship.angle = Math.atan2(dy, dx) + Math.PI / 2;

      // Move step-by-step along the angle vector
      state.ship.x += Math.cos(state.ship.angle - Math.PI / 2) * state.ship.speed;
      state.ship.y += Math.sin(state.ship.angle - Math.PI / 2) * state.ship.speed;
    }

    // 3. RENDER LOOP (Draws grid, target marker, and ship)
    function render() {
      ctx.clearRect(0, 0, canvas.width, canvas.height);

      // Draw subtle grid lines
      ctx.strokeStyle = '#131322';
      ctx.lineWidth = 1;
      for (let x = 0; x < canvas.width; x += 40) {
        ctx.beginPath(); ctx.moveTo(x, 0); ctx.lineTo(x, canvas.height); ctx.stroke();
      }
      for (let y = 0; y < canvas.height; y += 40) {
        ctx.beginPath(); ctx.moveTo(0, y); ctx.lineTo(canvas.width, y); ctx.stroke();
      }

      // Draw Target Marker if active
      if (state.target.active) {
        ctx.strokeStyle = '#38bdf8';
        ctx.lineWidth = 1.5;
        ctx.beginPath();
        ctx.arc(centerX + state.target.x, centerY + state.target.y, 6, 0, Math.PI * 2);
        ctx.stroke();
      }

      // Draw Ship
      ctx.save();
      ctx.translate(centerX + state.ship.x, centerY + state.ship.y);
      ctx.rotate(state.ship.angle);

      // Ship hull (pointing UP by default)
      ctx.fillStyle = '#22c55e';
      ctx.beginPath();
      ctx.moveTo(0, -14);
      ctx.lineTo(-9, 10);
      ctx.lineTo(0, 5);
      ctx.lineTo(9, 10);
      ctx.closePath();
      ctx.fill();

      ctx.restore();
    }

    // 4. MAIN GAME LOOP
    function gameLoop() {
      update();
      render();
      requestAnimationFrame(gameLoop);
    }

    gameLoop();
  </script>
</body>
</html>