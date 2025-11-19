<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RETAIL // SYSTEM</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Outfit:wght@200;400;600;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        mono: ['Space Mono', 'monospace'],
                    },
                    colors: {
                        'void': '#0a0f0d', // Dark Green-Blackish background
                        // MODIFIED: Mapped to dynamic CSS Variables for RGB Cycle
                        'gold-biz': 'var(--primary)', 
                        'emerald-biz': 'var(--secondary)',
                        'glass': 'rgba(255, 255, 255, 0.03)',
                    },
                    animation: {
                        'pulse-fast': 'pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'glitch': 'glitch 1s linear infinite',
                    },
                    keyframes: {
                        glitch: {
                            '2%, 64%': { transform: 'translate(2px,0) skew(0deg)' },
                            '4%, 60%': { transform: 'translate(-2px,0) skew(0deg)' },
                            '62%': { transform: 'translate(0,0) skew(5deg)' },
                        }
                    }
                },
            },
        }
    </script>

    <style>
        :root {
            /* MODIFIED: Initial values, updated by JS for RGB Gradient effect */
            --primary: hsl(0, 100%, 50%); 
            --secondary: hsl(180, 100%, 50%);
            --success: #00ff41;
        }

        body {
            background-color: #0a0f0d;
            margin: 0;
            overflow: hidden;
            color: white;
            cursor: none; /* Hide default cursor */
        }

        /* --- 1. CRT & SCANLINE EFFECTS (Context: Security Camera Feed) --- */
        .scanlines {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.2));
            background-size: 100% 4px;
            z-index: 9000;
            pointer-events: none;
            opacity: 0.4;
        }
        .vignette {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background: radial-gradient(circle, rgba(0,0,0,0) 60%, rgba(10,15,13,1) 100%);
            z-index: 9001;
            pointer-events: none;
        }

        /* --- 2. BOOT SEQUENCE (TERMINAL: Stock Loading) --- */
        #boot-screen {
            position: fixed;
            inset: 0;
            background: #050806;
            z-index: 10000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            font-family: 'Space Mono', monospace;
        }
        
        .terminal-log {
            font-size: 0.8rem;
            color: var(--primary);
            text-shadow: 0 0 5px var(--primary);
            opacity: 0.7;
            position: absolute;
            bottom: 20px;
            left: 20px;
            text-align: left;
            width: 350px;
            height: 150px;
            overflow: hidden;
            display: flex;
            flex-direction: column-reverse; /* Log flows up */
        }

        /* Boot Glitch */
        .boot-title-glitch {
            font-size: 5rem;
            font-weight: 800;
            position: relative;
            color: white;
            letter-spacing: -2px;
        }
        .boot-title-glitch::before, .boot-title-glitch::after {
            content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #050806;
        }
        .boot-title-glitch::before { left: 2px; text-shadow: -1px 0 var(--secondary); animation: glitch-anim-1 2s infinite linear alternate-reverse; }
        .boot-title-glitch::after { left: -2px; text-shadow: -1px 0 var(--primary); animation: glitch-anim-2 3s infinite linear alternate-reverse; }

        /* LOGIN HEADER GLITCH */
        .login-title-glitch {
            position: relative;
            display: inline-block;
        }
        .login-title-glitch::before, .login-title-glitch::after {
            content: attr(data-text);
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: transparent;
        }
        .login-title-glitch::before {
            left: 2px; text-shadow: -2px 0 var(--secondary); clip-path: inset(44% 0 61% 0);
            animation: glitch-anim-1 3s infinite linear alternate-reverse;
        }
        .login-title-glitch::after {
            left: -2px; text-shadow: -2px 0 var(--primary); clip-path: inset(44% 0 61% 0);
            animation: glitch-anim-2 2.5s infinite linear alternate-reverse;
        }

        @keyframes glitch-anim-1 {
            0% { clip-path: inset(20% 0 80% 0); } 20% { clip-path: inset(60% 0 10% 0); }
            40% { clip-path: inset(40% 0 50% 0); } 60% { clip-path: inset(80% 0 5% 0); }
            80% { clip-path: inset(10% 0 70% 0); } 100% { clip-path: inset(30% 0 20% 0); }
        }
        @keyframes glitch-anim-2 {
            0% { clip-path: inset(10% 0 60% 0); } 20% { clip-path: inset(30% 0 10% 0); }
            40% { clip-path: inset(80% 0 5% 0); } 60% { clip-path: inset(20% 0 70% 0); }
            80% { clip-path: inset(60% 0 20% 0); } 100% { clip-path: inset(15% 0 50% 0); }
        }

        /* CRT TURN ON ANIMATION */
        .crt-open {
            animation: turnOn 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }
        @keyframes turnOn {
            0% { transform: scale(1, 0.002); opacity: 1; filter: brightness(30); }
            30% { transform: scale(1, 0.002); opacity: 1; filter: brightness(10); }
            60% { transform: scale(1, 0.002); opacity: 1; }
            100% { transform: scale(1, 1); opacity: 1; filter: brightness(1); }
        }

        /* --- 3. HOLOGRAPHIC CARD (Business Card Look) --- */
        .holo-card {
            background: rgba(15, 25, 20, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
            position: relative;
            overflow: hidden;
            opacity: 1; 
        }

        .holo-card::before {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            background: conic-gradient(transparent, transparent, transparent, var(--primary));
            animation: rotateBorder 4s linear infinite; z-index: -1;
        }
        .holo-card::after {
            content: ''; position: absolute; inset: 1px;
            background: rgba(10, 15, 12, 0.95); z-index: -1; border-radius: 15px;
        }
        @keyframes rotateBorder {
            0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); }
        }

        /* --- 4. INPUTS --- */
        .tech-input {
            background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease; color: #fff;
        }
        .tech-input:focus {
            border-color: var(--primary); box-shadow: 0 0 15px var(--primary);
            background: rgba(255, 255, 255, 0.05); outline: none;
        }

        /* --- 5. RUNNING TEXT (MARQUEE - Stock Ticker Style) --- */
        .marquee-container {
            position: relative;
            width: 100%;
            overflow: hidden;
            mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 15%, black 85%, transparent);
        }
        .marquee-content {
            display: flex;
            white-space: nowrap;
            animation: scrollText 20s linear infinite;
        }
        @keyframes scrollText {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        /* --- 6. CURSOR --- */
        #cursor {
            position: fixed; top: 0; left: 0; width: 20px; height: 20px;
            border: 1px solid var(--primary); border-radius: 50%;
            transform: translate(-50%, -50%); pointer-events: none; z-index: 9999;
            transition: width 0.2s, height 0.2s, background-color 0.2s; mix-blend-mode: difference;
        }
        #cursor-dot {
            position: fixed; top: 0; left: 0; width: 4px; height: 4px;
            background: var(--primary); border-radius: 50%; transform: translate(-50%, -50%);
            pointer-events: none; z-index: 10000;
        }
        .cursor-hover {
            width: 50px !important; height: 50px !important;
            background-color: var(--primary); opacity: 0.3; border-color: transparent !important;
        }

        /* --- 7. SUCCESS SPLASH & TRANSITION EFFECTS --- */
        #success-overlay {
            position: fixed; inset: 0; background: rgba(5, 10, 5, 0.9); z-index: 9995;
            display: none; flex-direction: column; justify-content: center; align-items: center;
            backdrop-filter: blur(10px);
        }
        .access-text {
            font-family: 'Outfit', sans-serif;
            font-size: 4rem; font-weight: 900; color: var(--primary);
            text-shadow: 0 0 30px var(--primary); letter-spacing: -2px;
            position: relative;
            animation: scaleUp 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        .access-text::before {
            content: attr(data-text); position: absolute; left: 2px; text-shadow: -2px 0 white;
            top:0; width: 100%; background: transparent; overflow: hidden;
            animation: glitch-anim-1 2s infinite linear alternate-reverse;
        }
        @keyframes scaleUp {
            0% { transform: scale(0); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        #transition-flash {
            position: fixed; inset: 0; background: var(--primary); z-index: 10001;
            pointer-events: none; opacity: 0; transition: opacity 0.5s ease;
        }

    </style>
</head>
<body class="font-sans selection:bg-gold-biz selection:text-black">

    <div class="scanlines"></div>
    <div class="vignette"></div>
    <canvas id="warp-canvas" class="fixed inset-0 z-0"></canvas>
    
    <div id="transition-flash"></div>

    <div id="success-overlay">
        <div class="access-text" data-text="SHIFT STARTED">SHIFT STARTED</div>
        <div class="text-white font-mono mt-4 tracking-[0.5em] text-sm animate-pulse">MENGHUBUNGKAN KE DATABASE PENJUALAN...</div>
    </div>

    <div id="cursor"></div>
    <div id="cursor-dot"></div>

    <div id="boot-screen">
        <div class="boot-title-glitch" data-text="GDSS RETAIL">GDSS RETAIL</div>
        <div class="text-gold-biz font-mono text-sm mt-4 tracking-[0.5em] animate-pulse">SYSTEM INITIALIZING</div>
        <div class="terminal-log" id="terminal-output"></div>
        <div class="w-64 h-1 bg-gray-800 mt-6 relative overflow-hidden">
            <div id="boot-bar" class="absolute top-0 left-0 h-full bg-gold-biz shadow-[0_0_10px_var(--primary)] w-0 transition-all duration-[2500ms] ease-out"></div>
        </div>
    </div>

    <div id="main-interface" class="relative z-10 min-h-screen flex flex-col items-center justify-center p-4 opacity-1 scale-95">
        
        <div class="holo-card rounded-2xl p-1 w-full max-w-md group">
            <div class="bg-[#0a0f0d]/90 backdrop-blur-xl rounded-xl p-10 border border-white/5 relative z-10">
                
                <div class="text-center mb-12 relative">
                    <h2 class="login-title-glitch text-5xl font-black tracking-widest text-white mb-4 font-sans drop-shadow-[0_0_10px_var(--primary)]" data-text="LOGIN">
                        LOGIN
                    </h2>
                    <p class="text-xs font-mono text-gold-biz tracking-[0.4em] mb-6">GDSS RETAIL</p>
                    
                    <div class="marquee-container inline-block border-y border-white/10 bg-black/30 py-2 mb-2 w-full max-w-[300px]">
                        <div class="marquee-content">
                            <p class="text-[10px] md:text-xs font-mono text-gold-biz/80 tracking-[0.2em] uppercase px-4">
                                SELAMAT DATANG! SILAHKAN MASUKKAN EMAIL & PASSWORD ANDA
                            </p>
                            <p class="text-[10px] md:text-xs font-mono text-gold-biz/80 tracking-[0.2em] uppercase px-4">
                                SELAMAT DATANG! SILAHKAN MASUKKAN EMAIL & PASSWORD ANDA
                            </p>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 left-1/2 transform -translate-x-1/2 w-24 h-1 bg-gradient-to-r from-transparent via-gold-biz to-transparent shadow-[0_0_20px_var(--primary)] rounded-full"></div>
                </div>

                @if ($errors->any())
                <div class="mb-6 p-3 border border-red-500/50 bg-red-500/10 rounded text-center animate-glitch">
                    <p class="text-red-400 text-xs font-mono font-bold tracking-wider">>> ERROR: {{ $errors->first() }}</p>
                </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" class="space-y-6" id="login-form">
                    @csrf
                    
                    <div class="group relative">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 mb-1 font-mono group-focus-within:text-gold-biz transition-colors">EMAIL</label>
                        <input type="email" name="email" id="email" required autocomplete="off" 
                            class="tech-input w-full px-4 py-3 rounded text-sm font-mono tracking-wide placeholder-gray-700 focus:placeholder-gray-500"
                            placeholder="contoh : example@toko.com">
                    </div>

                    <div class="group relative">
                        <label class="block text-[10px] uppercase tracking-widest text-gray-500 mb-1 font-mono group-focus-within:text-emerald-biz transition-colors">PASSWORD</label>
                        <input type="password" name="password" id="password" required 
                            class="tech-input w-full px-4 py-3 rounded text-sm font-mono tracking-wide focus:border-emerald-biz focus:shadow-[0_0_15px_var(--secondary)]"
                            placeholder="••••••••••••">
                    </div>

                    <button type="submit" id="submit-btn" data-magnetic="true"
                        class="w-full py-4 bg-gold-biz text-black font-bold text-sm tracking-[0.2em] uppercase rounded mt-4 hover:bg-white transition-all duration-300 shadow-[0_0_20px_var(--primary)] relative overflow-hidden group">
                        <span class="relative z-10">MASUK TOKO</span>
                        <div class="absolute inset-0 bg-white transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300 z-0"></div>
                    </button>

                    <div class="mt-8 flex justify-between items-center text-[10px] font-mono text-gray-600">
                        <a href="#" class="hover:text-gold-biz transition-colors decoration-transparent border-b border-transparent hover:border-gold-biz pb-0.5">DIBUAT OLEH BAGUS ACHMAD SYAHPUTRA</a>
                        <span class="flex items-center gap-2">
                            TOKO BUKA
                            <span class="block w-1.5 h-1.5 bg-emerald-500 rounded-full shadow-[0_0_5px_var(--secondary)] animate-pulse"></span>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="mt-8 text-[10px] font-mono text-gray-600 tracking-[0.3em] opacity-50">
            INTEGRATED GDSS MANAGEMENT
            @2025
        </div>
    </div>

    <script>
        // --- 1. BOOT SEQUENCE (Business Context) ---
        const bootScreen = document.getElementById('boot-screen');
        const terminalOutput = document.getElementById('terminal-output');
        const bootBar = document.getElementById('boot-bar');
        const mainInterface = document.getElementById('main-interface');

        const logs = [
            "Memuat modul akuntansi...", "Sinkronisasi stok barang...", "Menghubungkan ke Payment Gateway...",
            "Enkripsi data transaksi...", "Memeriksa jadwal shift...", "Optimasi dashboard kasir...", "Toko siap beroperasi."
        ];

        async function runBootSequence() {
            setTimeout(() => { bootBar.style.width = "100%"; }, 100);

            for (let i = 0; i < logs.length; i++) {
                await new Promise(r => setTimeout(r, Math.random() * 300 + 100));
                const p = document.createElement('div');
                p.innerText = `> ${logs[i]}`;
                terminalOutput.prepend(p);
            }

            setTimeout(() => {
                bootScreen.innerHTML = ""; 
                bootScreen.style.background = "black";
                bootScreen.style.display = "none";
                mainInterface.style.opacity = "1";
                mainInterface.classList.add('crt-open');
                targetSpeed = 0.5;
            }, 500);
        }

        window.addEventListener('load', runBootSequence);

        // --- 2. DATA FLOW PARTICLES (RGB WARP & COLOR CYCLE) ---
        const canvas = document.getElementById('warp-canvas');
        const ctx = canvas.getContext('2d');
        let width, height, stars = [], warpSpeed = 0, targetSpeed = 40;
        
        // ADDED: Global Hue for RGB Cycling
        let hue = 0;
        
        function resize() {
            width = window.innerWidth; height = window.innerHeight;
            canvas.width = width; canvas.height = height;
        }
        window.addEventListener('resize', resize);
        resize();

        for(let i = 0; i < 800; i++) {
            stars.push({ x: Math.random() * width - width/2, y: Math.random() * height - height/2, z: Math.random() * width });
        }

        function drawStars() {
            // Background color trace
            ctx.fillStyle = "rgba(10, 15, 13, 0.4)"; ctx.fillRect(0, 0, width, height);
            
            warpSpeed += (targetSpeed - warpSpeed) * 0.05;
            const cx = width / 2; const cy = height / 2;

            // ADDED: RGB CYCLE LOGIC (Berubah-ubah warna)
            hue = (hue + 0.5) % 360; // Speed of color change
            const colorPrimary = `hsl(${hue}, 100%, 50%)`;
            const colorSecondary = `hsl(${(hue + 180) % 360}, 100%, 50%)`;

            // Update CSS Variables dynamically for Neon Theme
            document.documentElement.style.setProperty('--primary', colorPrimary);
            document.documentElement.style.setProperty('--secondary', colorSecondary);

            stars.forEach(star => {
                star.z -= warpSpeed;
                if(star.z <= 0) { star.z = width; star.x = Math.random() * width - width/2; star.y = Math.random() * height - height/2; }
                const x = cx + (star.x / star.z) * width;
                const y = cy + (star.y / star.z) * width;
                const size = (1 - star.z / width) * 3;
                const length = Math.max(size, (warpSpeed * 2) * (1 - star.z / width)); 
                const alpha = (1 - star.z / width);
                
                // Color: Neon RGB Flow
                ctx.fillStyle = `hsla(${hue}, 100%, 50%, ${alpha})`;
                ctx.beginPath();
                ctx.ellipse(x, y, length, size, Math.atan2(y - cy, x - cx), 0, Math.PI * 2);
                ctx.fill();
            });
            requestAnimationFrame(drawStars);
        }
        drawStars();

        // --- 3. TYPING REACTIVITY ---
        const inputs = document.querySelectorAll('input');
        let typeTimer;
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                if(!isSubmitting) {
                    targetSpeed = 30; clearTimeout(typeTimer);
                    typeTimer = setTimeout(() => { targetSpeed = 0.5; }, 200);
                }
            });
        });

        // --- 4. MAGNETIC CURSOR ---
        const cursor = document.getElementById('cursor');
        const cursorDot = document.getElementById('cursor-dot');
        let mouseX = 0, mouseY = 0, cursorX = 0, cursorY = 0;

        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX; mouseY = e.clientY;
            cursorDot.style.left = mouseX + 'px'; cursorDot.style.top = mouseY + 'px';
        });

        function animateCursor() {
            cursorX += (mouseX - cursorX) * 0.15; cursorY += (mouseY - cursorY) * 0.15;
            cursor.style.left = cursorX + 'px'; cursor.style.top = cursorY + 'px';
            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        const targets = document.querySelectorAll('button, input, a');
        targets.forEach(target => {
            target.addEventListener('mouseenter', () => { cursor.classList.add('cursor-hover'); });
            target.addEventListener('mouseleave', () => { cursor.classList.remove('cursor-hover'); });
        });


        // --- 5. LOGIN SUCCESS LOGIC & TRANSITION ---
        const loginForm = document.getElementById('login-form');
        const successOverlay = document.getElementById('success-overlay');
        const flashOverlay = document.getElementById('transition-flash');
        let isSubmitting = false;

        loginForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            if(email && password) {
                isSubmitting = true;
                
                successOverlay.style.display = "flex";
                
                // Warp speed to max (Transaction successful feel)
                targetSpeed = 100;

                setTimeout(() => {
                    flashOverlay.style.opacity = "1";
                    setTimeout(() => {
                        loginForm.submit(); 
                    }, 600);
                }, 2000); 
            }
        });

    </script>
</body>
</html>