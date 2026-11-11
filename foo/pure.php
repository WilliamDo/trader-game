<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Pure Separation Movement Demo</title>
  <style>
    body {
      background: #18181b;
      color: #f4f4f5;
      font-family: monospace;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    canvas {
      background: #27272a;
      border: 2px solid #52525b;
      border-radius: 6px;
    }
    p { margin-top: 16px; color: #a1a1aa; }
  </style>
</head>
<body>

<canvas id="canvas" width="400" height="400"></canvas>
<p>Hold Arrow Keys. Watch the model state drive the view.</p>

<script>
  // ==========================================
  // 1. PURE MODEL (Pure Data)
  // ==========================================
  const TILE_SIZE = 40;
  const GRID_COLS = 10;
  const GRID_ROWS = 10;

  const state = {
    // Character grid coordinates
    player: { x: 0, y: 0 },
    // Keyboard inputs currently held down
    keysHeld: {
      ArrowUp: false,
      ArrowDown: false,
      ArrowLeft: false,
      ArrowRight: false
    }
  };


  // ==========================================
  // 2. INPUT LISTENERS (Update Key Flags Only)
  // ==========================================
  window.addEventListener('keydown', (e) => {
    if (e.key in state.keysHeld) {
      state.keysHeld[e.key] = true;
      e.preventDefault(); // Stop browser page scroll
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
  // Runs 5 times per second (every 200ms).
  // Mutates the grid position based ONLY on key state and boundaries.
  function updateModel() {
    if (state.keysHeld.ArrowUp && state.player.y > 0) {
      state.player.y -= 1;
    }
    if (state.keysHeld.ArrowDown && state.player.y < GRID_ROWS - 1) {
      state.player.y += 1;
    }
    if (state.keysHeld.ArrowLeft && state.player.x > 0) {
      state.player.x -= 1;
    }
    if (state.keysHeld.ArrowRight && state.player.x < GRID_COLS - 1) {
      state.player.x += 1;
    }
  }

  // Ticks independent of rendering!
  setInterval(updateModel, 80); 


  // ==========================================
  // 4. PURE VIEW LOOP (Canvas Rendering)
  // ==========================================
  const canvas = document.getElementById('canvas');
  const ctx = canvas.getContext('2d');

  // Simply reads state and renders what it finds on screen
  function render() {
    // Wipe screen
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Draw Grid Lines
    ctx.strokeStyle = '#3f3f46';
    for (let c = 0; c < GRID_COLS; c++) {
      for (let r = 0; r < GRID_ROWS; r++) {
        ctx.strokeRect(c * TILE_SIZE, r * TILE_SIZE, TILE_SIZE, TILE_SIZE);
      }
    }

    // Draw Character from state coordinates
    const px = state.player.x * TILE_SIZE;
    const py = state.player.y * TILE_SIZE;
    
    ctx.fillStyle = '#6366f1'; // Indigo square
    ctx.fillRect(px + 4, py + 4, TILE_SIZE - 8, TILE_SIZE - 8);

    // Keep render loop running
    requestAnimationFrame(render);
  }

  // Start rendering
  render();
</script>

</body>
</html>