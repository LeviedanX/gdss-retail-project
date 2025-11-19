<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GDSS RETAIL // @yield('title', 'SYSTEM')</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:ital,wght@0,400;0,700;1,400&family=Outfit:wght@200;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
                        'void': '#05000a', 
                        'purple-neon': '#d946ef', 
                        'cyan-neon': '#00e5ff', 
                    },
                    animation: {
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
            --primary: #d946ef;
            --secondary: #00e5ff;
        }

        body {
            background-color: #05000a;
            margin: 0;
            color: white;
            cursor: none;
            overflow-x: hidden;
        }

        /* --- SCROLLBAR --- */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #05000a; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }

        /* --- BACKGROUND EFFECTS --- */
        .scanlines {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.2));
            background-size: 100% 4px; z-index: 40; pointer-events: none; opacity: 0.3;
        }
        .vignette {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: radial-gradient(circle, rgba(0,0,0,0) 60%, rgba(5,0,10,1) 100%);
            z-index: 41; pointer-events: none;
        }

        /* --- NAVBAR NEON STYLES --- */
        .glass-nav {
            background: rgba(5, 0, 10, 0.9);
            backdrop-filter: blur(20px);
            border-top: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 -10px 30px rgba(0,0,0,0.8);
        }

        .nav-item {
            position: relative; display: flex; flex-direction: column; align-items: center;
            color: #4b5563; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 0.5rem 1.5rem;
        }
        
        .nav-item i { font-size: 1.4rem; margin-bottom: 6px; transition: transform 0.3s; text-shadow: 0 0 0 transparent; }
        .nav-item span { font-family: 'Space Mono', monospace; font-size: 0.7rem; letter-spacing: 1px; font-weight: bold; opacity: 0.7;}

        /* Hover & Active States - COLORFUL NEON */
        .nav-item.cyan:hover, .nav-item.cyan.active { color: #00e5ff; }
        .nav-item.cyan:hover i, .nav-item.cyan.active i { text-shadow: 0 0 15px #00e5ff; transform: translateY(-4px); }
        
        .nav-item.green:hover, .nav-item.green.active { color: #4ade80; }
        .nav-item.green:hover i, .nav-item.green.active i { text-shadow: 0 0 15px #4ade80; transform: translateY(-4px); }

        .nav-item.yellow:hover, .nav-item.yellow.active { color: #facc15; }
        .nav-item.yellow:hover i, .nav-item.yellow.active i { text-shadow: 0 0 15px #facc15; transform: translateY(-4px); }

        .nav-item::after {
            content: ''; position: absolute; bottom: 5px; width: 4px; height: 4px;
            background: currentColor; border-radius: 50%; opacity: 0; box-shadow: 0 0 10px currentColor;
            transition: all 0.3s;
        }
        .nav-item.active::after { opacity: 1; width: 20px; height: 1px; border-radius: 0; bottom: 0; }


        /* --- SPOTLIGHT LOGOUT BUTTON --- */
        .spotlight-btn {
            position: relative;
            background: rgba(20, 0, 0, 0.3);
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 8px;
            overflow: hidden;
            cursor: none;
            transition: border-color 0.3s;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            padding: 0 25px;
            height: 45px;
        }

        .spotlight-btn:hover { border-color: rgba(239, 68, 68, 0.8); }

        .spotlight-text {
            color: #ef4444;
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
            font-weight: bold;
            letter-spacing: 0.2em;
            z-index: 20;
            pointer-events: none;
        }

        .spotlight-btn::before {
            content: '';
            position: absolute;
            top: var(--y, 50%);
            left: var(--x, 50%);
            transform: translate(-50%, -50%);
            width: 80px; height: 80px;
            background: radial-gradient(circle, rgba(217, 70, 239, 0.4) 0%, rgba(217, 70, 239, 0) 70%);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
            z-index: 10;
            border-radius: 50%;
        }

        .spotlight-btn:hover::before { opacity: 1; }

        /* ========================================= */
        /* ===  RGB NEON GRADIENT ANIMATIONS     === */
        /* ========================================= */

        @keyframes rgb-flow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes rgb-border {
            0% { border-color: #ff0000; box-shadow: 0 0 5px #ff0000; }
            20% { border-color: #ff00ff; box-shadow: 0 0 5px #ff00ff; }
            40% { border-color: #0000ff; box-shadow: 0 0 5px #0000ff; }
            60% { border-color: #00ffff; box-shadow: 0 0 5px #00ffff; }
            80% { border-color: #00ff00; box-shadow: 0 0 5px #00ff00; }
            100% { border-color: #ff0000; box-shadow: 0 0 5px #ff0000; }
        }

        @keyframes rgb-bg-cycle {
            0% { background-color: #ff0000; }
            20% { background-color: #ff00ff; }
            40% { background-color: #0000ff; }
            60% { background-color: #00ffff; }
            80% { background-color: #00ff00; }
            100% { background-color: #ff0000; }
        }

        /* 1. GRADIENT TEXT (Header) */
        .rgb-text-gradient {
            background: linear-gradient(270deg, #ff0000, #ff00ff, #0000ff, #00ffff, #00ff00, #ffff00, #ff0000);
            background-size: 400% 400%;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: rgb-flow 5s ease infinite;
        }

        /* 2. RGB BAR (Transition & Header Line) */
        .rgb-bar-gradient {
            background: linear-gradient(90deg, #ff0000, #ff00ff, #0000ff, #00ffff, #00ff00, #ffff00, #ff0000);
            background-size: 200% 200%;
            animation: rgb-flow 3s linear infinite;
            box-shadow: 0 0 10px rgba(255,255,255,0.2);
        }

        /* 3. RGB BORDER (Cursor & Avatar) */
        .rgb-border-anim {
            animation: rgb-border 4s linear infinite;
        }

        /* 4. RGB FILL (Cursor Dot) */
        .rgb-fill-anim {
            animation: rgb-bg-cycle 4s linear infinite;
        }

        /* --- BOOT SCREEN --- */
        #boot-screen {
            position: fixed; inset: 0; background: #05000a; z-index: 9999;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            font-family: 'Space Mono', monospace;
        }
        .boot-title-glitch {
            font-size: 3rem; font-weight: 800; position: relative; color: white; letter-spacing: -2px;
        }
        .boot-title-glitch::before { left: 2px; text-shadow: -1px 0 var(--secondary); animation: glitch 2s infinite; content: attr(data-text); position: absolute; top: 0; width: 100%; height: 100%; background: #05000a;}
        .boot-title-glitch::after { left: -2px; text-shadow: -1px 0 var(--primary); animation: glitch 3s infinite; content: attr(data-text); position: absolute; top: 0; width: 100%; height: 100%; background: #05000a;}

        /* --- RGB CURSOR --- */
        #cursor {
            position: fixed; top: 0; left: 0; width: 20px; height: 20px;
            border: 1px solid white; border-radius: 50%;
            transform: translate(-50%, -50%); pointer-events: none; 
            z-index: 99999; transition: width 0.2s, height 0.2s; mix-blend-mode: difference;
        }
        #cursor-dot {
            position: fixed; top: 0; left: 0; width: 4px; height: 4px;
            background: white; border-radius: 50%; transform: translate(-50%, -50%);
            pointer-events: none; z-index: 99999;
        }
        .cursor-hover { width: 50px !important; height: 50px !important; background-color: rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="font-sans selection:bg-purple-neon selection:text-white flex flex-col min-h-screen">

    <div class="scanlines"></div>
    <div class="vignette"></div>
    <canvas id="warp-canvas" class="fixed inset-0 z-0"></canvas>
    
    <div id="cursor" class="rgb-border-anim"></div>
    <div id="cursor-dot" class="rgb-fill-anim"></div>

    <div id="boot-screen">
        <div class="boot-title-glitch" data-text="GDSS RETAIL">GDSS RETAIL</div>
        <div class="rgb-text-gradient font-mono text-xs mt-4 tracking-[0.5em] animate-pulse">SYSTEM LOADING...</div>
        <div class="w-64 h-1 bg-gray-900 mt-6 relative overflow-hidden rounded">
            <div id="boot-bar" class="absolute top-0 left-0 h-full rgb-bar-gradient w-0 transition-all duration-[2000ms] ease-out"></div>
        </div>
    </div>

    <div id="main-interface" class="opacity-0 transition-opacity duration-1000 flex flex-col h-screen overflow-hidden relative z-10">

        <header class="h-20 fixed top-0 w-full z-50 flex justify-between items-center px-8 bg-[#05000a]/90 backdrop-blur-sm border-b border-white/5">
            
            <div class="flex items-center gap-3">
                <div class="h-8 w-1 rgb-bar-gradient"></div>
                <div>
                    <h1 class="text-2xl font-black font-mono tracking-tighter rgb-text-gradient">GDSS</h1>
                    <p class="text-[10px] rgb-text-gradient tracking-[0.3em] -mt-1 opacity-80">DECISION SUPPORT</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-sm font-bold font-mono text-white">{{ Auth::user()->name }}</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest font-mono">
                        {{ str_replace('_', ' ', Auth::user()->role) }}
                    </p>
                </div>
                <div class="h-10 w-10 rounded-full border-2 rgb-border-anim bg-white/10 flex items-center justify-center text-white font-bold font-mono">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto pt-24 pb-32 px-4 md:px-10 relative scroll-smooth">
            @yield('content')
            
            <div class="mt-10 mb-4 text-center">
                <p class="text-[10px] font-mono text-gray-700 tracking-widest">GDSS PROJECT 2025</p>
            </div>
        </main>

        <nav class="glass-nav h-24 fixed bottom-0 w-full z-50 px-8 flex items-center justify-between">
            
            <div class="w-32 hidden md:block"></div>

            <div class="flex flex-1 justify-center items-center gap-8 md:gap-16">
                
                <a href="{{ url('/dashboard/' . (Auth::user()->role == 'area_manager' ? 'area' : (Auth::user()->role == 'store_manager' ? 'store' : 'hr'))) }}" 
                   class="nav-item cyan {{ Request::is('dashboard*') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('evaluation.index') }}" 
                   class="nav-item green {{ Request::is('evaluation*') ? 'active' : '' }}">
                    <i class="fas fa-pen-to-square"></i>
                    <span>Penilaian</span>
                </a>

                @if(Auth::user()->role == 'area_manager')
                <a href="{{ route('consensus.index') }}" 
                   class="nav-item yellow {{ Request::is('consensus*') ? 'active' : '' }}">
                    <i class="fas fa-trophy"></i>
                    <span>Konsensus</span>
                </a>
                @endif

            </div>

            <div class="w-auto flex justify-end border-l border-white/10 pl-8 ml-4">
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                
                <button onclick="toggleShutdown()" class="spotlight-btn group">
                    <i class="fas fa-power-off text-red-500 z-20 relative"></i>
                    <span class="spotlight-text">LOGOUT</span>
                </button>
            </div>
        </nav>

    </div>

    <div id="shutdown-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center backdrop-blur-sm bg-black/80 transition-opacity duration-300 opacity-0">
        <div class="border border-red-500/50 shadow-[0_0_50px_rgba(239,68,68,0.2)] p-1 max-w-sm w-full transform scale-90 transition-transform duration-300" id="modal-box" style="background: #0a0505;">
            <div class="p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent"></div>
                <div class="text-center mb-6 relative z-10">
                    <i class="fas fa-power-off text-4xl text-red-500 animate-pulse mb-4"></i>
                    <h3 class="font-mono text-xl font-bold text-white tracking-tighter">PEMBERITAHUAN</h3>
                    <p class="font-mono text-[10px] text-red-400 tracking-[0.2em] mt-2">Tetap ingin logout?</p>
                </div>
                <div class="flex gap-3 relative z-10">
                    <button onclick="toggleShutdown()" class="flex-1 py-3 border border-gray-700 text-gray-400 font-mono text-xs hover:bg-gray-800 hover:text-white transition-colors uppercase">Batal</button>
                    <button onclick="confirmLogout()" class="flex-1 py-3 bg-red-600 text-black font-bold font-mono text-xs hover:bg-red-500 hover:shadow-[0_0_20px_rgba(220,38,38,0.6)] transition-all tracking-wider uppercase">Logout</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // 1. BOOT ANIMATION
        window.addEventListener('load', () => {
            const bootBar = document.getElementById('boot-bar');
            const bootScreen = document.getElementById('boot-screen');
            const mainInterface = document.getElementById('main-interface');
            setTimeout(() => { bootBar.style.width = "100%"; }, 100);
            setTimeout(() => {
                bootScreen.style.opacity = "0";
                setTimeout(() => { bootScreen.style.display = "none"; }, 500);
                mainInterface.style.opacity = "1";
            }, 1500);
        });

        // 2. WARP DRIVE BACKGROUND
        const canvas = document.getElementById('warp-canvas');
        const ctx = canvas.getContext('2d');
        let width, height, stars = [], warpSpeed = 0.2;
        function resize() {
            width = window.innerWidth; height = window.innerHeight;
            canvas.width = width; canvas.height = height;
        }
        window.addEventListener('resize', resize);
        resize();
        for(let i = 0; i < 400; i++) {
            stars.push({ x: Math.random() * width - width/2, y: Math.random() * height - height/2, z: Math.random() * width });
        }
        function drawStars() {
            ctx.fillStyle = "rgba(5, 0, 10, 0.5)"; 
            ctx.fillRect(0, 0, width, height);
            const cx = width / 2; const cy = height / 2;
            stars.forEach(star => {
                star.z -= warpSpeed;
                if(star.z <= 0) { star.z = width; star.x = Math.random() * width - width/2; star.y = Math.random() * height - height/2; }
                const x = cx + (star.x / star.z) * width;
                const y = cy + (star.y / star.z) * width;
                const size = (1 - star.z / width) * 2.5;
                const alpha = (1 - star.z / width);
                ctx.fillStyle = `rgba(217, 70, 239, ${alpha})`;
                ctx.beginPath();
                ctx.arc(x, y, size, 0, Math.PI * 2);
                ctx.fill();
            });
            requestAnimationFrame(drawStars);
        }
        drawStars();

        // 3. MAGNETIC CURSOR
        const cursor = document.getElementById('cursor');
        const cursorDot = document.getElementById('cursor-dot');
        let mouseX = 0, mouseY = 0, cursorX = 0, cursorY = 0;
        document.addEventListener('mousemove', (e) => { mouseX = e.clientX; mouseY = e.clientY; cursorDot.style.left = mouseX + 'px'; cursorDot.style.top = mouseY + 'px'; });
        function animateCursor() {
            cursorX += (mouseX - cursorX) * 0.15; cursorY += (mouseY - cursorY) * 0.15;
            cursor.style.left = cursorX + 'px'; cursor.style.top = cursorY + 'px';
            requestAnimationFrame(animateCursor);
        }
        animateCursor();
        document.body.addEventListener('mouseover', (e) => {
            if (e.target.closest('a, button, .nav-item')) {
                cursor.classList.add('cursor-hover');
            } else {
                cursor.classList.remove('cursor-hover');
            }
        });

        // 4. SPOTLIGHT EFFECT FOR LOGOUT BUTTON
        const spotlightBtn = document.querySelector('.spotlight-btn');
        if(spotlightBtn) {
            spotlightBtn.addEventListener('mousemove', (e) => {
                const rect = spotlightBtn.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                spotlightBtn.style.setProperty('--x', x + 'px');
                spotlightBtn.style.setProperty('--y', y + 'px');
            });
        }

        // 5. MODAL LOGIC
        const modal = document.getElementById('shutdown-modal');
        const modalBox = document.getElementById('modal-box');
        function toggleShutdown() {
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0'); modal.classList.add('flex');
                    modalBox.classList.remove('scale-90'); modalBox.classList.add('scale-100');
                }, 10);
            } else {
                modal.classList.add('opacity-0');
                modalBox.classList.remove('scale-100'); modalBox.classList.add('scale-90');
                setTimeout(() => { modal.classList.add('hidden'); modal.classList.remove('flex'); }, 300);
            }
        }
        function confirmLogout() {
            document.body.style.filter = "brightness(0) blur(10px)";
            document.body.style.transition = "all 0.5s";
            setTimeout(() => { document.getElementById('logout-form').submit(); }, 800);
        }
        modal.addEventListener('click', (e) => { if (e.target === modal) toggleShutdown(); });
    </script>
</body>
</html>