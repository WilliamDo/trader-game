<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Simple Timer Animation</title>
  <style>
    body {
      background: #222;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    canvas {
      background: #ffffff;
      border-radius: 8px;
    }
  </style>
</head>
<body>

<canvas id="canvas" width="400" height="300"></canvas>

<script>
  const canvas = document.getElementById('canvas');
  const ctx = canvas.getContext('2d');

  let x = 50;

  function updateAndDraw() {
    // 1. Clear the screen
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // 2. Draw the circle at the current 'x' position
    ctx.beginPath();
    ctx.arc(x, 150, 25, 0, Math.PI * 2);
    ctx.fillStyle = '#0984e3';
    ctx.fill();

    // 3. Update position for next time
    x = x + 3;
    if (x > canvas.width + 25) {
      x = -25;
    }
  }

  // Run 'updateAndDraw' every 30 milliseconds (~33 times a second)
  setInterval(updateAndDraw, 5);
</script>

</body>
</html>