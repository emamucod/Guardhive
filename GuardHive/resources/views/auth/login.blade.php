<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guard Hive</title>
    <style>
       @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Rajdhani:wght@300;500&family=Titillium+Web:wght@400;600&display=swap');

/* Base Styles */
body {
    margin: 0;
    overflow: hidden;
    font-family: 'Orbitron', sans-serif;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: black;
}

canvas {
    position: absolute;
    top: 0;
    left: 0;
    z-index: 1;
}

/* Login Container */
.login-container {
    background: rgba(255, 255, 255, 0.1);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 30px rgba(0, 247, 255, 0.2);
    text-align: center;
    width: 350px;
    backdrop-filter: blur(10px);
    position: relative;
    z-index: 10;
    border: 1px solid rgba(0, 247, 255, 0.3);
}

/* Title */
.login-title {
    font-family: 'Titillium Web', sans-serif;
    font-size: 2.2rem;
    margin-bottom: 30px;
    letter-spacing: 2px;
    position: relative;
    display: inline-block;
}

/* Input Fields */
.input-field {
    width: 90%;
    padding: 12px;
    margin: 10px 0;
    border: none;
    background: rgba(255, 255, 255, 0.15);
    color: white;
    border-radius: 6px;
    font-size: 16px;
    border-bottom: 1px solid transparent;
    transition: all 0.3s ease;
}

.input-field:focus {
    outline: none;
    background: rgba(255, 255, 255, 0.25);
    border-bottom: 2px solid #00f7ff;
    box-shadow: 0 2px 15px rgba(0, 247, 255, 0.3);
}

.input-field::placeholder {
    color: rgba(255, 255, 255, 0.6);
}

/* Login Button */
.login-btn {
    width: 100%;
    padding: 14px;
    background: transparent;
    color: #00f7ff;
    border: 1px solid rgba(0, 247, 255, 0.6);
    border-radius: 6px;
    cursor: pointer;
    font-size: 16px;
    font-weight: bold;
    letter-spacing: 1px;
    margin-top: 20px;
    transition: all 0.3s ease;
}

.login-btn:hover {
    background: rgba(0, 247, 255, 0.1);
    box-shadow: 0 0 15px rgba(0, 247, 255, 0.4);
}

/* Forgot Password Link */
.forgot-password {
    display: block;
    margin-top: 15px;
    color: rgba(0, 247, 255, 0.8);
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
    text-align: right;
    padding-right: 5%;
}

.forgot-password:hover {
    color: #00f7ff;
    text-shadow: 0 0 5px rgba(0, 247, 255, 0.5);
}

/* Error Messages */
.error-message {
    color: #ff4d4d;
    margin-bottom: 15px;
    text-align: left;
    padding-left: 5%;
    font-size: 14px;
}

/* Status Message */
.status-message {
    color: #00ff88;
    margin-bottom: 15px;
    font-size: 14px;
}

/* Responsive Adjustments */
@media (max-width: 480px) {
    .login-container {
        width: 85%;
        padding: 30px 20px;
    }
    
    .login-title {
        font-size: 1.8rem;
    }
}
    </style>
</head>
<body>
    <canvas id="starsCanvas"></canvas>
    
    <div class="login-container" style="color: rgba(0, 247, 255, 0.8)">
        <h1 class="login-title">GUARD HIVE</h1>
        
        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Session Status -->
        @if (session('status'))
            <div class="status-message">
                {{ session('status') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Email/Username Field -->
            <div>
                <input id="email" class="input-field" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="abc@example.com">
            </div>

            <!-- Password Field -->
            <div>
                <input id="password" class="input-field" type="password" name="password" required placeholder="enter password">
            </div>

            <!-- Remember Me Checkbox (Hidden by default) -->
            <div style="display: none;">
                <input type="checkbox" id="remember_me" name="remember">
            </div>

            <!-- Forgot Password Link -->
            @if (Route::has('password.request'))
                <a class="forgot-password" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <!-- Login Button -->
            <button type="submit" class="login-btn">
                {{ __('Login') }}
            </button>
        </form>
    </div>
    
    <script>
       const canvas = document.getElementById("starsCanvas");
const ctx = canvas.getContext("2d");
canvas.width = window.innerWidth;
canvas.height = window.innerHeight;

// Config
const MAX_CONNECTIONS = 3;
const PULSE_SPEED = 0.05;

const stars = [];
const numStars = 500;

for (let i = 0; i < numStars; i++) {
    stars.push({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        radius: Math.random() > 0.8 ? 2.5 : 1.2,
        originalRadius: Math.random() > 0.8 ? 2.5 : 1.2,
        speedX: (Math.random() - 0.5) * 0.15,
        speedY: (Math.random() - 0.5) * 0.15,
        connections: [],
        pulse: Math.random() * Math.PI * 2
    });
}

function updateConnections() {
    stars.forEach(star => star.connections = []);
    
    for (let i = 0; i < stars.length; i++) {
        const potentialConnections = [];
        
        for (let j = 0; j < stars.length; j++) {
            if (i === j) continue;
            const dx = stars[i].x - stars[j].x;
            const dy = stars[i].y - stars[j].y;
            const distance = Math.sqrt(dx * dx + dy * dy);
            if (distance < 150) {
                potentialConnections.push({ index: j, distance });
            }
        }
        
        potentialConnections.sort((a, b) => a.distance - b.distance);
        for (let k = 0; k < Math.min(MAX_CONNECTIONS, potentialConnections.length); k++) {
            if (stars[i].connections.length >= MAX_CONNECTIONS) break;
            const targetIndex = potentialConnections[k].index;
            if (!stars[i].connections.includes(targetIndex)) {
                stars[i].connections.push(targetIndex);
            }
        }
    }
}

updateConnections();

function drawStars() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    
    stars.forEach((star, i) => {
        star.connections.forEach(j => {
            if (j > i) return;
            const target = stars[j];
            const pulse = (Math.sin(star.pulse) * 0.05 + 0.3);
            ctx.beginPath();
            ctx.moveTo(star.x, star.y);
            ctx.lineTo(target.x, target.y);
            ctx.strokeStyle = `rgba(100, 220, 255, ${pulse})`;
            ctx.lineWidth = 0.8;
            ctx.stroke();
        });
    });
    
    stars.forEach(star => {
        ctx.beginPath();
        const sides = 6;
        const size = star.radius;
        for (let i = 0; i < sides; i++) {
            const angle = (i * 2 * Math.PI / sides) + Math.PI / 6;
            const x = star.x + size * Math.cos(angle);
            const y = star.y + size * Math.sin(angle);
            if (i === 0) ctx.moveTo(x, y);
            else ctx.lineTo(x, y);
        }
        ctx.closePath();
        ctx.fillStyle = `rgba(255, 255, 255, ${star.radius > 2 ? 0.9 : 0.6})`;
        ctx.fill();
        star.pulse += PULSE_SPEED;
    });
}

let mouseX = canvas.width / 2;
let mouseY = canvas.height / 2;
canvas.addEventListener("mousemove", (event) => {
    mouseX = event.clientX;
    mouseY = event.clientY;
});

function animate() {
    drawStars();
    
    stars.forEach(star => {
        let dx = mouseX - star.x;
        let dy = mouseY - star.y;
        let distance = Math.sqrt(dx * dx + dy * dy);
        if (distance < 150) {
            star.x += dx * 0.01;
            star.y += dy * 0.01;
            star.radius = star.originalRadius * 1.5;
        } else {
            star.x += star.speedX;
            star.y += star.speedY;
            star.radius = star.originalRadius;
        }
        if (star.x < 0 || star.x > canvas.width) star.speedX *= -1;
        if (star.y < 0 || star.y > canvas.height) star.speedY *= -1;
    });
    
    if (Math.random() < 0.01) updateConnections();
    
    requestAnimationFrame(animate);
}
animate();
    </script>
</body>
</html>