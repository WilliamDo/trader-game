<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Spaceship Grid with HTML HUD Overlay</title>
  <style>
    body {
      background: #050508;
      color: #e2e8f0;
      font-family: monospace;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    /* Container holding both the canvas and the HTML overlay */
    .game-container {
      position: relative;
      width: 400px;
      height: 400px;
      border: 2px solid #1e1e2f;
      border-radius: 8px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8);
      overflow: hidden;
    }

    canvas {
      background: #0b0b12;
      display: block;
    }

    /* HTML HUD Overlay positioned directly on top of the canvas */
    .hud-overlay {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 12px 16px;
      box-sizing: border-box;
      background: linear-gradient(to bottom, rgba(11, 11, 18, 0.9) 0%, rgba(11, 11, 18, 0) 100%);
      pointer-events: none; /* Allows clicks to pass through if needed */
      font-size: 12px;
      letter-spacing: 1px;
    }

    .fuel-container {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    /* HTML/CSS Fuel Bar */
    .fuel-bar-track {
      width: 60px;
      height: 8px;
      border: 1px solid #475569;
      background: #1e293b;
      border-radius: 2px;
      overflow: hidden;
    }

    .fuel-bar-fill {
      width: 100%;
      height: 100%;
      background: #22c55e;
      transition: width 0.1s linear, background-color 0.2s;
    }

    p { margin-top: 16px; color: #64748b; font-size: 14px; }
  </style>
</head>
<body>

<div class="game-container">
  <!-- HTML HUD OVERLAY -->
  <div class="hud-overlay">
    <span id="hud-sector">SEC: ALPHA-9</span>
    <span id="hud-score" style="color: #38bdf8;">SCORE: 0</span>
    <div class="fuel-container">
      <span style="color: #94a3b8;">FUEL:</span>
      <div class="fuel-bar-track">
        <div id="hud-fuel-fill" class="fuel-bar-fill"></div>
      </div>
    </div>
  </div>

  <!-- CANVAS GAME WORLD -->
  <canvas id="canvas" width="400" height="400"></canvas>
</div>

<p>Hold <strong>Arrow Keys</strong> to pilot your ship across the grid.</p>

<script>
  // ==========================================
  // 1. PURE MODEL (Pure Data)
  // ==========================================
  const TILE_SIZE = 40;
  const GRID_COLS = 10;
  const GRID_ROWS = 10;

  const state = {
    ship: {
      x: 4,
      y: 4,
      angle: 0
    },
    stats: {
      fuel: 100,
      score: 0,
      sector: 'ALPHA-9'
    },
    keysHeld: {
      ArrowUp: false,
      ArrowDown: false,
      ArrowLeft: false,
      ArrowRight: false
    }
  };


  // ==========================================
  // 2. INPUT LISTENERS
  // ==========================================
  window.addEventListener('keydown', (e) => {
    if (e.key in state.keysHeld) {
      state.keysHeld[e.key] = true;
      e.preventDefault();
    }
  });

  window.addEventListener('keyup', (e) => {
    if (e.key in state.keysHeld) {
      state.keysHeld[e.key] = false;
    }
  });


  // ==========================================
  // 3. PURE LOGIC LOOP (State Mutation)
  // ==========================================
  function updateModel() {
    let moved = false;

    if (state.keysHeld.ArrowUp && state.ship.y > 0) {
      state.ship.y -= 1;
      state.ship.angle = -Math.PI / 2;
      moved = true;
    }
    else if (state.keysHeld.ArrowDown && state.ship.y < GRID_ROWS - 1) {
      state.ship.y += 1;
      state.ship.angle = Math.PI / 2;
      moved = true;
    }
    else if (state.keysHeld.ArrowLeft && state.ship.x > 0) {
      state.ship.x -= 1;
      state.ship.angle = Math.PI;
      moved = true;
    }
    else if (state.keysHeld.ArrowRight && state.ship.x < GRID_COLS - 1) {
      state.ship.x += 1;
      state.ship.angle = 0;
      moved = true;
    }

    if (moved) {
      state.stats.score += 10;
      if (state.stats.fuel > 0) {
        state.stats.fuel = Math.max(0, state.stats.fuel - 2);
      }
    }
  }

  setInterval(updateModel, 150);


  // ==========================================
  // 4. HTML DOM SYNC (HUD View)
  // ==========================================
  const scoreEl = document.getElementById('hud-score');
  const fuelFillEl = document.getElementById('hud-fuel-fill');

  function updateHUDDOM() {
    scoreEl.innerText = `SCORE: ${state.stats.score}`;
    fuelFillEl.style.width = `${state.stats.fuel}%`;

    // Change fuel bar color dynamically using CSS
    if (state.stats.fuel > 50) {
      fuelFillEl.style.backgroundColor = '#22c55e'; // Green
    } else if (state.stats.fuel > 20) {
      fuelFillEl.style.backgroundColor = '#eab308'; // Yellow
    } else {
      fuelFillEl.style.backgroundColor = '#ef4444'; // Red
    }
  }


  // ==========================================
  // 5. PURE CANVAS VIEW LOOP (Game World)
  // ==========================================
  const canvas = document.getElementById('canvas');
  const ctx = canvas.getContext('2d');

  function render() {
    // Clear canvas
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw Space Grid
    ctx.strokeStyle = '#131322';
    ctx.lineWidth = 1;
    for (let c = 0; c < GRID_COLS; c++) {
      for (let r = 0; r < GRID_ROWS; r++) {
        ctx.strokeRect(c * TILE_SIZE, r * TILE_SIZE, TILE_SIZE, TILE_SIZE);
      }
    }

    // Calculate ship pixel center
    const pixelX = state.ship.x * TILE_SIZE + TILE_SIZE / 2;
    const pixelY = state.ship.y * TILE_SIZE + TILE_SIZE / 2;

    ctx.save();
    ctx.translate(pixelX, pixelY);
    ctx.rotate(state.ship.angle);

    // Draw Spaceship
    ctx.fillStyle = '#38bdf8';
    ctx.beginPath();
    ctx.moveTo(16, 0);
    ctx.lineTo(-12, -10);
    ctx.lineTo(-6, 0);
    ctx.lineTo(-12, 10);
    ctx.closePath();
    ctx.fill();

    // Engine Glow
    ctx.fillStyle = '#f43f5e';
    ctx.fillRect(-10, -3, 4, 6);

    ctx.restore();

    // Update the HTML HUD overlay elements
    updateHUDDOM();

    requestAnimationFrame(render);
  }

  // Start render loop
  render();
</script>

</body>
</html>