<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Guard Hive - ESP32-CAM Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@400;700&family=Titillium+Web:wght@400;600&display=swap" rel="stylesheet" />
    <style>
        /* Paste all your CSS from your <style> here */
        /* Keep the existing styles exactly as you have */
        /* For example: */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Rajdhani', sans-serif;
        }
        
        h1, h2, h3, .menu-item, .top-bar {
            font-family: 'Orbitron', sans-serif;
            letter-spacing: 1px;
        }

        body {
            display: flex;
            height: 100vh;
            background: #0a0a0a;
            color: white;
            overflow: hidden;
        }

        /* Sidebar - Cyberpunk Style */
        .sidebar {
            width: 250px;
            background: rgba(30, 30, 30, 0.8);
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-right: 1px solid rgba(0, 247, 255, 0.3);
            backdrop-filter: blur(10px);
            z-index: 10;
        }

        .sidebar h2 {
            margin-bottom: 30px;
            color: #00f7ff;
            text-shadow: 0 0 10px rgba(0, 247, 255, 0.5);
            position: relative;
        }

        .sidebar h2::after {
            content: "";
            position: absolute;
            bottom: -8px;
            left: 25%;
            width: 50%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #00f7ff, transparent);
        }

        .menu-item {
            width: 100%;
            padding: 15px;
            text-align: center;
            background: rgba(37, 37, 37, 0.7);
            margin: 10px 0;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            color: #ccc;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(0, 247, 255, 0.1);
            border-left: 3px solid #00f7ff;
            color: white;
            box-shadow: 0 0 15px rgba(0, 247, 255, 0.2);
        }

        /* Main Content Area */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-y: auto;
            max-height: 100vh;
        }

        /* Animated Background */
        .bg-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Top Bar */
        .top-bar {
            height: 70px;
            background: rgba(30, 30, 30, 0.8);
            display: flex;
            align-items: center;
            padding: 0 30px;
            justify-content: space-between;
            border-bottom: 1px solid rgba(0, 247, 255, 0.3);
            backdrop-filter: blur(10px);
            z-index: 10;
        }

        .top-bar h3 {
            color: #00f7ff;
            font-size: 1.3rem;
        }

        .status-indicator {
            display: flex;
            align-items: center;
        }

        .status-indicator::before {
            content: "";
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: limegreen;
            margin-right: 8px;
            box-shadow: 0 0 10px limegreen;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }

        /* Stream Container */
        .stream-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .stream-box {
            width: 80%;
            max-width: 900px;
            height: 500px;
            border-radius: 8px;
            border: 1px solid rgba(0, 247, 255, 0.5);
            box-shadow: 0 0 30px rgba(0, 247, 255, 0.2);
            overflow: hidden;
            background-color: #111;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .stream-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .stream-placeholder {
            color: #666;
            font-size: 1.2rem;
            text-align: center;
            padding: 20px;
        }

        .stream-error {
            color: #ff5555;
            font-size: 1rem;
            margin-top: 10px;
            text-align: center;
        }

        /* Controls */
        .controls {
            margin-top: 30px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }

        button {
            padding: 12px 25px;
            font-size: 16px;
            margin: 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .start-btn {
            background: transparent;
            color: #00f7ff;
            border: 1px solid rgba(0, 247, 255, 0.6);
        }

        .stop-btn {
            background: transparent;
            color: #ff3a3a;
            border: 1px solid rgba(255, 58, 58, 0.6);
        }

        .capture-btn {
            background: transparent;
            color: #ffcc00;
            border: 1px solid rgba(255, 204, 0, 0.6);
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 247, 255, 0.3);
        }

        .start-btn:hover {
            background: rgba(0, 247, 255, 0.1);
        }

        .stop-btn:hover {
            background: rgba(255, 58, 58, 0.1);
        }

        .capture-btn:hover {
            background: rgba(255, 204, 0, 0.1);
        }

        button::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right, 
                transparent 45%, 
                rgba(0, 247, 255, 0.4) 50%, 
                transparent 55%
            );
            transform: rotate(30deg);
            animation: scan 3s linear infinite;
            opacity: 0.7;
            z-index: -1;
        }

        .stop-btn::after {
            background: linear-gradient(
                to bottom right, 
                transparent 45%, 
                rgba(255, 58, 58, 0.4) 50%, 
                transparent 55%
            );
        }

        .capture-btn::after {
            background: linear-gradient(
                to bottom right, 
                transparent 45%, 
                rgba(255, 204, 0, 0.4) 50%, 
                transparent 55%
            );
        }

        @keyframes scan {
            0% { transform: translateY(-100%) rotate(30deg); }
            100% { transform: translateY(100%) rotate(30deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 200px;
            }
            
            .stream-box {
                width: 95%;
                height: 300px;
            }
            
            .controls {
                flex-direction: column;
                width: 80%;
            }
            
            button {
                width: 100%;
            }
            #bgCanvas {
  position: fixed; /* or absolute */
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 0; /* lowest */
  pointer-events: none; /* para di niya harangin clicks */
}

.main-content, .monitoring-content {
  position: relative;
  z-index: 10; /* mas mataas para lumabas sa ibabaw ng canvas */
  background-color: rgba(10,10,10,0.8); /* optional: para hindi ganun ka-transparent */
  padding: 1rem;
  border-radius: 10px; /* optional para mas nice tingnan */
}
            

        }
    </style>
</head>
<body>
    <canvas id="bgCanvas" class="bg-animation"></canvas>

    <div class="sidebar">
        <h2>GUARD HIVE</h2>
        <div class="menu-item active">
            <a href="{{ route('dashboard') }}" style="color: inherit; text-decoration: none; display: block;">Dashboard</a>
        </div>
        <div class="menu-item">
            <a href="{{ route('monitoring') }}" style="color: inherit; text-decoration: none; display: block;">Monitoring</a>
        </div>
        <div class="menu-item">
            <a>Settings</a>
        </div>
    </div>

    <main class="main-content">
        @if (isset($header))
            <div class="top-bar">
                <h3>{{ $header }}</h3>
                <div class="status-indicator">
                    <p>Status: <span id="connection-status">Offline</span></p>
                </div>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="stop-btn" style="padding: 8px 16px; font-size: 14px;">
                        LOGOUT
                    </button>
                </form>
            </div>
        @endif

        {{ $slot }}
    </main>

    <script>
          // Background animation
        const canvas = document.getElementById("bgCanvas");
        const ctx = canvas.getContext("2d");
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        const stars = [];
        const numStars = 150;
        
        for (let i = 0; i < numStars; i++) {
            stars.push({
                x: Math.random() * canvas.width,
                y: Math.random() * canvas.height,
                radius: Math.random() * 1.5,
                originalRadius: Math.random() * 1.5,
                speedX: (Math.random() - 0.5) * 0.1,
                speedY: (Math.random() - 0.5) * 0.1
            });
        }

        function drawStars() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "rgba(255, 255, 255, 0.8)";
            
            stars.forEach(star => {
                ctx.beginPath();
                ctx.arc(star.x, star.y, star.radius, 0, Math.PI * 2);
                ctx.fill();
            });

            ctx.strokeStyle = "rgba(100, 220, 255, 0.2)";
            ctx.lineWidth = 0.5;
            
            for (let i = 0; i < stars.length; i++) {
                for (let j = i + 1; j < stars.length; j++) {
                    const dx = stars[i].x - stars[j].x;
                    const dy = stars[i].y - stars[j].y;
                    const distance = Math.sqrt(dx * dx + dy * dy);
                    
                    if (distance < 150) {
                        ctx.beginPath();
                        ctx.moveTo(stars[i].x, stars[i].y);
                        ctx.lineTo(stars[j].x, stars[j].y);
                        ctx.stroke();
                    }
                }
            }
        }

        function animate() {
            drawStars();
            stars.forEach(star => {
                star.x += star.speedX;
                star.y += star.speedY;
                
                if (star.x < 0 || star.x > canvas.width) star.speedX *= -1;
                if (star.y < 0 || star.y > canvas.height) star.speedY *= -1;
            });
            
            requestAnimationFrame(animate);
        }
        animate();

        // Stream control functions
        const streamImg = document.getElementById('stream');
        const placeholder = document.getElementById('stream-placeholder');
        const errorDisplay = document.getElementById('stream-error');
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');
        const captureBtn = document.getElementById('capture-btn');
        const statusDisplay = document.getElementById('connection-status');
        
        // Try multiple common ESP32-CAM stream endpoints
        const streamUrls = [
        "http://192.168.5.171:5000/stream"
            ];
        
        let currentStreamUrl = "";
        let connectionCheckInterval;
        let isStreaming = false;

        // Create a canvas for capturing frames
        const captureCanvas = document.createElement('canvas');
        const captureCtx = captureCanvas.getContext('2d');

        startBtn.addEventListener('click', startStream);
        stopBtn.addEventListener('click', stopStream);
        captureBtn.addEventListener('click', captureImage);

        function startStream() {
            if (isStreaming) return;
            
            // Try each URL until one works
            let urlIndex = 0;
            
            function tryNextUrl() {
                if (urlIndex >= streamUrls.length) {
                    errorDisplay.textContent = "Error: Could not connect to any known stream endpoints";
                    return;
                }
                
                currentStreamUrl = streamUrls[urlIndex];
                streamImg.src = currentStreamUrl;
                placeholder.textContent = "Connecting to " + currentStreamUrl + "...";
                errorDisplay.textContent = "";
                
                // Check if the image loads successfully
                streamImg.onload = function() {
                    streamImg.style.display = "block";
                    placeholder.style.display = "none";
                    startBtn.textContent = "STREAMING...";
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                    captureBtn.disabled = false;
                    statusDisplay.textContent = "Online";
                    statusDisplay.style.color = "limegreen";
                    isStreaming = true;
                    
                    // Start periodic connection checking
                    clearInterval(connectionCheckInterval);
                    connectionCheckInterval = setInterval(checkStreamConnection, 3000);
                };
                
                streamImg.onerror = function() {
                    urlIndex++;
                    if (urlIndex < streamUrls.length) {
                        setTimeout(tryNextUrl, 500);
                    } else {
                        errorDisplay.textContent = "Error: Could not connect to camera stream";
                        placeholder.textContent = "Connection failed. Check camera IP and power.";
                        isStreaming = false;
                    }
                };
            }
            
            tryNextUrl();
        }

        function stopStream() {
            clearInterval(connectionCheckInterval);
            streamImg.src = "";
            streamImg.style.display = "none";
            placeholder.style.display = "block";
            placeholder.textContent = "Stream stopped. Click 'START STREAM' to begin.";
            startBtn.textContent = "START STREAM";
            startBtn.disabled = false;
            stopBtn.disabled = true;
            captureBtn.disabled = true;
            statusDisplay.textContent = "Offline";
            statusDisplay.style.color = "#ff5555";
            errorDisplay.textContent = "";
            isStreaming = false;
        }

        function checkStreamConnection() {
            if (!isStreaming) return;
            
            // Create a test image to check if stream is still alive
            const testImg = new Image();
            testImg.src = currentStreamUrl + "?" + new Date().getTime(); // Add timestamp to avoid caching
            
            testImg.onload = function() {
                // Stream is still working
                statusDisplay.textContent = "Online";
                statusDisplay.style.color = "limegreen";
                errorDisplay.textContent = "";
            };
            
            testImg.onerror = function() {
                // Stream has failed
                statusDisplay.textContent = "Connection lost";
                statusDisplay.style.color = "orange";
                errorDisplay.textContent = "Connection to camera lost. Trying to reconnect...";
                
                // Attempt to reconnect
                stopStream();
                setTimeout(startStream, 2000);
            };
        }

        function captureImage() {
            if (!isStreaming) return;
            
            // Set canvas dimensions to match video stream
            captureCanvas.width = streamImg.videoWidth || streamImg.naturalWidth;
            captureCanvas.height = streamImg.videoHeight || streamImg.naturalHeight;
            
            // Draw the current frame to canvas
            captureCtx.drawImage(streamImg, 0, 0, captureCanvas.width, captureCanvas.height);
            
            // Convert canvas to image and trigger download
            captureCanvas.toBlob(function(blob) {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                
                // Create filename with timestamp
                const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
                a.download = `esp32-capture-${timestamp}.jpg`;
                
                // Trigger download
                document.body.appendChild(a);
                a.click();
                
                // Clean up
                setTimeout(() => {
                    document.body.removeChild(a);
                    window.URL.revokeObjectURL(url);
                }, 100);
                
                // Visual feedback
                captureBtn.textContent = "CAPTURED!";
                setTimeout(() => {
                    captureBtn.textContent = "CAPTURE IMAGE";
                }, 1000);
            }, 'image/jpeg', 0.9);
        }

        // Initialize with stream stopped
        stopStream();
    </script>
</body>
</html>
