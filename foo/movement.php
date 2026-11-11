<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Canvas Grid Vehicle Movement</title>
  <style>
    body {
      background: #1e1e24;
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
      background: #2b2b36;
      border: 3px solid #444;
      border-radius: 6px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    }
    p {
      margin-top: 12px;
      color: #aaa;
    }
  </style>
</head>
<body>

<canvas id="gridCanvas" width="500" height="500"></canvas>
<p>Use <strong>Arrow Keys</strong> to move the vehicle on the grid.</p>

<script>
  const canvas = document.getElementById('gridCanvas');
  const ctx = canvas.getContext('2d');

  // 1. Grid Settings
  const tileSize = 50; // Size of each grid square in pixels
  const cols = canvas.width / tileSize; // 10 columns
  const rows = canvas.height / tileSize; // 10 rows

  // 2. Vehicle State (Grid coordinates)
  const vehicle = {
    x: 0,        // Start at column 0
    y: 0,        // Start at row 0
    angle: 0     // Angle in degrees to face travel direction
  };

  // 3. Listen for keydown events
  window.addEventListener('keydown', (event) => {
    switch(event.key) {
      case 'ArrowUp':
        if (vehicle.y > 0) vehicle.y--;
        vehicle.angle = -90; // Face up
        break;
      case 'ArrowDown':
        if (vehicle.y < rows - 1) vehicle.y++;
        vehicle.angle = 90; // Face down
        break;
      case 'ArrowLeft':
        if (vehicle.x > 0) vehicle.x--;
        vehicle.angle = 180; // Face left
        break;
      case 'ArrowRight':
        if (vehicle.x < cols - 1) vehicle.x++;
        vehicle.angle = 0; // Face right
        break;
      default:
        return; // Ignore other keys
    }
    
    // Prevent arrow keys from scrolling the browser page
    event.preventDefault();
    
    // Redraw the canvas immediately after moving
    draw();
  });

  // 4. Draw the Grid
  function drawGrid() {
    ctx.strokeStyle = '#3a3a4c';
    ctx.lineWidth = 1;

    for (let i = 0; i <= cols; i++) {
      ctx.beginPath();
      ctx.moveTo(i * tileSize, 0);
      ctx.lineTo(i * tileSize, canvas.height);
      ctx.stroke();
    }

    for (let j = 0; j <= rows; j++) {
      ctx.beginPath();
      ctx.moveTo(0, j * tileSize);
      ctx.lineTo(canvas.width, j * tileSize);
      ctx.stroke();
    }
  }

  // 5. Draw the Vehicle
  function drawVehicle() {
    // Convert grid coordinates to pixel center of the tile
    const pixelX = vehicle.x * tileSize + tileSize / 2;
    const pixelY = vehicle.y * tileSize + tileSize / 2;

    ctx.save(); // Save default state
    ctx.translate(pixelX, pixelY); // Move origin to vehicle center
    ctx.rotate((vehicle.angle * Math.PI) / 180); // Rotate vehicle to facing direction

    // Body (Car)
    ctx.fillStyle = '#ff4757';
    ctx.fillRect(-18, -12, 36, 24);

    // Windshield
    ctx.fillStyle = '#70a1ff';
    ctx.fillRect(2, -10, 8, 20);

    // Headlights
    ctx.fillStyle = '#ffa502';
    ctx.fillRect(16, -10, 3, 5);
    ctx.fillRect(16, 5, 3, 5);

    ctx.restore(); // Restore default state
  }

  // 6. Main render function
  function draw() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    drawGrid();
    drawVehicle();
  }

  // Initial render on load
  draw();
</script>

</body>
</html>