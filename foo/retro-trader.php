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

    // Ship 2: Top-Left fleet wingman (-100, -80 relative to center)
    drawShip(-100, 0, '#22c55e');

    // Ship 3: Bottom-Right fleet wingman (100, 80 relative to center)
    drawShip(100, 0, '#f59e0b');
  </script>
</body>
</html>