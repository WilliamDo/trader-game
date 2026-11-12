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

  <canvas id="map" width="500" height="500"></canvas>

  <script>
    const canvas = document.getElementById('map');
    const ctx = canvas.getContext('2d');

    const centerX = canvas.width / 2;
    const centerY = canvas.height / 2;

    const state = {
      ship: {
        x: -80,          // Current X relative to center
        y: -200,          // Current Y relative to center
        speed: 2.5,    // Pixels moved per frame
        angle: 0       // Rotation heading
      },
      target: {
        x: 0,          // Target X relative to center
        y: 0,          // Target Y relative to center
        active: false  // Is it currently moving?
      }
    };


    function drawShip(hullColor = '#38bdf8') {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.save();

      // Translate origin to canvas center + relative offset
      ctx.translate(centerX + state.ship.x, centerY + state.ship.y);

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

    async function fetchGameState() {
        try {
            // 1. Send the HTTP request to your PHP script
            const response = await fetch('position.php?player_name=player-qSpe5xD1');

            // 2. Check if the response was successful (e.g., status 200)
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            // 3. Parse the incoming JSON response
            const data = await response.json();

            // 4. Update your global variables with the result
            state.ship.x = data.x;
            state.ship.y = data.y;

            console.log("Game state updated successfully:", data);

        } catch (error) {
            console.error("Failed to fetch game state:", error);
        }
    }

    // 4. MAIN GAME LOOP
    function gameLoop() {
      fetchGameState();
      drawShip();
    }

    setInterval(gameLoop, 250);
  </script>
</body>
</html>