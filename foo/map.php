<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Canvas World Map Exploration</title>
  <style>
    body {
      background: #111;
      color: #fff;
      font-family: sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }
    canvas {
      background: #0984e3;
      border: 4px solid #444;
      border-radius: 8px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.6);
    }
    p {
      margin-top: 15px;
      color: #aaa;
    }
  </style>
</head>
<body>

<!-- Canvas size is 400x400 pixels -->
<canvas id="mapCanvas" width="400" height="400"></canvas>
<p>Use <strong>Arrow Keys</strong> to pan across the world map.</p>

<script>
  const canvas = document.getElementById('mapCanvas');
  const ctx = canvas.getContext('2d');

  // Grid and Viewport Settings
  const tileSize = 40; // Each tile is 40x40 pixels
  const viewportCols = canvas.width / tileSize;   // 10 columns visible on screen
  const viewportRows = canvas.height / tileSize;  // 10 rows visible on screen

  // The Big World Map Size (30x30 tiles, much larger than our 10x10 canvas)
  const worldCols = 30;
  const worldRows = 30;

  // Camera position (which tile coordinate is at the top-left of our canvas)
  let cameraX = 10;
  let cameraY = 10;

  // Procedural generator to give every tile in the world a type based on its coordinates
  function getTileType(x, y) {
    // Using math waves to simulate continents, islands, and terrain types
    const val = Math.sin(x * 0.3) + Math.cos(y * 0.3);
    if (val > 0.6)  return 'mountain'; // Gray peaks
    if (val > -0.2) return 'land';     // Green grass
    if (val > -0.6) return 'sand';     // Yellow beaches
    return 'water';                    // Blue oceans
  }

  // Draw the visible section of the map
  function drawMap() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // Loop through what is visible in the viewport window
    for (let r = 0; r < viewportRows; r++) {
      for (let c = 0; c < viewportCols; c++) {
        // Calculate the actual position on the big world map
        const worldX = cameraX + c;
        const worldY = cameraY + r;

        // Get terrain type for this specific world tile
        const tile = getTileType(worldX, worldY);

        // Pick color based on tile type
        if (tile === 'water') ctx.fillStyle = '#0984e3';
        else if (tile === 'sand') ctx.fillStyle = '#ffeaa7';
        else if (tile === 'land') ctx.fillStyle = '#00b894';
        else if (tile === 'mountain') ctx.fillStyle = '#b2bec3';

        // Draw the tile on the canvas
        ctx.fillRect(c * tileSize, r * tileSize, tileSize, tileSize);

        // Draw a subtle grid line for the pixelated retro look
        ctx.strokeStyle = 'rgba(0,0,0,0.15)';
        ctx.strokeRect(c * tileSize, r * tileSize, tileSize, tileSize);
      }
    }

    // Draw a red outline box in the center to highlight your focal point
    ctx.strokeStyle = '#ff7675';
    ctx.lineWidth = 3;
    ctx.strokeRect(
      (viewportCols / 2 - 0.5) * tileSize, 
      (viewportRows / 2 - 0.5) * tileSize, 
      tileSize, 
      tileSize
    );
  }

  // Listen for arrow keys to pan the camera around the world
  window.addEventListener('keydown', (e) => {
    let moved = false;

    if (e.key === 'ArrowUp' && cameraY > 0) {
      cameraY--;
      moved = true;
    } else if (e.key === 'ArrowDown' && cameraY < worldRows - viewportRows) {
      cameraY++;
      moved = true;
    } else if (e.key === 'ArrowLeft' && cameraX > 0) {
      cameraX--;
      moved = true;
    } else if (e.key === 'ArrowRight' && cameraX < worldCols - viewportCols) {
      cameraX++;
      moved = true;
    }

    if (moved) {
      e.preventDefault(); // Stop page from scrolling
      drawMap();
    }
  });

  // Initial draw when page loads
  drawMap();
</script>

</body>
</html>