<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
                        'void': '#050505',
                        'glass': 'rgba(255, 255, 255, 0.03)',
                    },
                    animation: {
                        'rgb-border': 'rgbBorder 3s linear infinite',
                        'rgb-text': 'rgbText 5s linear infinite',
                        'pulse-fast': 'pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        rgbBorder: {
                            '0%, 100%': { borderColor: '#ff0000', boxShadow: '0 0 10px #ff0000' },
                            '33%': { borderColor: '#00ff00', boxShadow: '0 0 10px #00ff00' },
                            '66%': { borderColor: '#0000ff', boxShadow: '0 0 10px #0000ff' },
                        },
                        rgbText: {
                            '0%, 100%': { color: '#ff0080', textShadow: '0 0 10px #ff0080' },
                            '25%': { color: '#7928ca', textShadow: '0 0 10px #7928ca' },
                            '50%': { color: '#ff0080', textShadow: '0 0 10px #ff0080' },
                            '75%': { color: '#00dfd8', textShadow: '0 0 10px #00dfd8' },
                        }
                    }
                },
            },
        }
    </script>

    <style>
        body {
            background-color: #050505;
            margin: 0;
            overflow: hidden; /* Hide scrollbars for the aesthetic */
            color: white;
            cursor: none; /* Custom cursor */
        }

        /* --- RGB GRADIENT ANIMATION CLASS --- */
        .rgb-gradient-text {
            background: linear-gradient(to right, #ff0000, #ffff00, #00ff00, #00ffff, #0000ff, #ff00ff, #ff0000);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            background-size: 200% auto;
            animation: rainbow 5s linear infinite;
        }
        
        .rgb-gradient-border {
            position: relative;
            border: 2px solid transparent;
            background-clip: padding-box; 
            border-radius: 10px;
        }
        .rgb-gradient-border::after {
            content: '';
            position: absolute;
            top: -2px; bottom: -2px;
            left: -2px; right: -2px;
            background: linear-gradient(45deg, #ff0000, #00ff00, #0000ff, #ff0000);
            z-index: -1;
            border-radius: 10px;
            background-size: 400%;
            animation: rainbow 20s linear infinite;
        }

        @keyframes rainbow {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* --- CRT & SCANLINE EFFECTS --- */
        .scanlines {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.2));
            background-size: 100% 4px; z-index: 9000; pointer-events: none; opacity: 0.4;
        }
        .vignette {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: radial-gradient(circle, rgba(0,0,0,0) 60%, rgba(0,0,0,1) 100%);
            z-index: 9001; pointer-events: none;
        }

        /* --- HOLO CARD --- */
        .holo-card {
            background: rgba(10, 10, 10, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.8);
        }

        /* --- CURSOR --- */
        #cursor {
            position: fixed; top: 0; left: 0; width: 20px; height: 20px;
            border: 1px solid white; border-radius: 50%;
            transform: translate(-50%, -50%); pointer-events: none; z-index: 9999;
            transition: width 0.2s, height 0.2s; mix-blend-mode: difference;
        }
        #cursor-dot {
            position: fixed; top: 0; left: 0; width: 4px; height: 4px;
            background: white; border-radius: 50%; transform: translate(-50%, -50%);
            pointer-events: none; z-index: 10000;
        }
        .cursor-hover {
            width: 50px !important; height: 50px !important;
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* --- BOOT SCREEN --- */
        #boot-screen {
            position: fixed; inset: 0; background: #000; z-index: 10000;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            font-family: 'Space Mono', monospace;
        }
    </style>
</head>
<body class="antialiased text-white font-sans selection:bg-white selection:text-black">

    <div class="scanlines"></div>
    <div class="vignette"></div>
    
    <canvas id="warp-canvas" class="fixed inset-0 z-0"></canvas>

    <div id="boot-screen">
        <div class="rgb-gradient-text text-6xl font-black tracking-tighter mb-4">SYSTEM BOOT</div>
        <div class="text-xs font-mono text-gray-500 tracking-[0.5em] animate-pulse">LOADING LARAVEL ENVIRONMENT...</div>
        <div class="w-64 h-1 bg-gray-800 mt-6 relative overflow-hidden rounded-full">
            <div id="boot-bar" class="absolute top-0 left-0 h-full bg-white shadow-[0_0_15px_rgba(255,255,255,0.8)] w-0 transition-all duration-[2000ms] ease-out"></div>
        </div>
    </div>

    <div id="cursor"></div>
    <div id="cursor-dot"></div>

    <div id="main-interface" class="relative z-10 min-h-screen flex flex-col items-center justify-center opacity-0 transition-opacity duration-1000">

        @if (Route::has('login'))
            <nav class="fixed top-0 right-0 p-6 flex gap-4 z-50">
                @auth
                    <a href="{{ url('/dashboard') }}" class="rgb-gradient-border px-6 py-2 text-xs font-mono tracking-widest hover:scale-105 transition-transform bg-black/50">
                        <span class="relative z-10 rgb-gradient-text font-bold">DASHBOARD</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group relative px-6 py-2 overflow-hidden border border-white/20 hover:border-white/50 transition-all">
                        <div class="absolute inset-0 bg-white/5 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                        <span class="relative font-mono text-xs tracking-widest text-gray-300 group-hover:text-white">LOG IN</span>
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rgb-gradient-border px-6 py-2 text-xs font-mono tracking-widest hover:scale-105 transition-transform bg-black/50">
                            <span class="relative z-10 rgb-gradient-text font-bold">REGISTER</span>
                        </a>
                    @endif
                @endauth
            </nav>
        @endif

        <div class="holo-card rounded-2xl p-1 w-full max-w-4xl mx-4 lg:mx-0 relative group">
            <div class="bg-black/80 backdrop-blur-xl rounded-xl p-8 md:p-12 border border-white/5 relative z-10 overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    
                    <div class="space-y-8">
                        <div class="relative">
                            <svg class="h-16 w-auto text-white animate-pulse-fast drop-shadow-[0_0_15px_rgba(255,255,255,0.5)]" viewBox="0 0 62 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M61.8548 14.6253C61.8778 14.7102 61.8895 14.7978 61.8897 14.8858V28.5615C61.8898 28.737 61.8434 28.9095 61.7554 29.0614C61.6675 29.2132 61.5409 29.3392 61.3887 29.4265L49.9104 36.0351V49.1337C49.9104 49.4902 49.7209 49.8192 49.4118 49.9987L25.4519 63.7916C25.3971 63.8227 25.3372 63.8427 25.2774 63.8639C25.255 63.8714 25.2338 63.8851 25.2101 63.8913C25.0426 63.9354 24.8666 63.9354 24.6991 63.8913C24.6716 63.8838 24.6467 63.8689 24.6205 63.8589C24.5657 63.8389 24.5084 63.8215 24.456 63.7916L0.501061 49.9987C0.348882 49.9113 0.222437 49.7853 0.134469 49.6334C0.0465019 49.4816 0.000120578 49.3092 0 49.1337L0 8.10652C0 8.01678 0.0124642 7.92953 0.0348998 7.84477C0.0423783 7.8161 0.0598282 7.78993 0.0697995 7.76126C0.0884958 7.69772 0.115936 7.63666 0.152083 7.58055L0.152083 7.58055C0.15333 7.57931 0.154577 7.57806 0.155823 7.57681C0.205729 7.50945 0.271843 7.45457 0.34912 7.41342L24.6943 1.38955C24.9335 1.32969 25.1897 1.32969 25.4289 1.38955L49.5559 7.41342C49.6494 7.46331 49.7279 7.53562 49.7803 7.62418L61.6455 14.4657C61.7266 14.5118 61.7965 14.5642 61.8548 14.6253ZM24.9562 22.6773L36.6085 15.9659L24.9562 9.25451L13.3039 15.9659L24.9562 22.6773ZM47.5381 46.9991L37.2322 41.0652L25.5799 47.7766L35.8857 53.7105L47.5381 46.9991ZM24.3324 47.7766L12.6801 41.0652L2.37424 46.9991L14.0266 53.7105L24.3324 47.7766ZM2.37424 20.7205L12.6801 26.6544L24.3324 19.9429L14.0266 14.0091L2.37424 20.7205ZM37.2322 26.6544L47.5381 20.7205L37.2322 14.7866L26.9263 20.7205L37.2322 26.6544Z" stroke="url(#paint0_linear)" stroke-width="2"/>
                                <defs>
                                    <linearGradient id="paint0_linear" x1="0" y1="0" x2="62" y2="65" gradientUnits="userSpaceOnUse">
                                        <stop stop-color="#ff0000"><animate attributeName="stop-color" values="#ff0000;#00ff00;#0000ff;#ff0000" dur="4s" repeatCount="indefinite" /></stop>
                                        <stop offset="1" stop-color="#0000ff"><animate attributeName="stop-color" values="#0000ff;#ff0000;#00ff00;#0000ff" dur="4s" repeatCount="indefinite" /></stop>
                                    </linearGradient>
                                </defs>
                            </svg>
                        </div>

                        <div>
                            <h1 class="text-4xl font-bold font-mono mb-2 rgb-gradient-text">LARAVEL SYSTEM</h1>
                            <p class="text-sm text-gray-400 font-mono leading-relaxed">
                                A rich ecosystem for modern web development.
                                <br>Initialized and ready for deployment.
                            </p>
                        </div>

                        <div class="h-px w-full bg-gradient-to-r from-transparent via-white/30 to-transparent"></div>

                        <div class="flex gap-4 font-mono text-xs">
                             <a href="https://laravel.com/docs" target="_blank" class="flex items-center gap-2 hover:text-white text-gray-400 transition-colors">
                                <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                                DOCUMENTATION
                            </a>
                            <a href="https://laracasts.com" target="_blank" class="flex items-center gap-2 hover:text-white text-gray-400 transition-colors">
                                <span class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></span>
                                LARACASTS
                            </a>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="absolute -inset-4 bg-gradient-to-r from-pink-600 to-purple-600 opacity-20 blur-2xl rounded-full animate-pulse"></div>
                        
                        <div class="relative space-y-3">
                            <a href="https://laravel-news.com" class="block group">
                                <div class="bg-white/5 border border-white/10 p-4 rounded hover:bg-white/10 transition-all duration-300 group-hover:border-pink-500/50">
                                    <div class="flex justify-between items-center">
                                        <span class="font-mono text-sm font-bold text-gray-300 group-hover:text-pink-400">>> LARAVEL NEWS</span>
                                        <span class="text-xs text-gray-600 group-hover:text-white opacity-0 group-hover:opacity-100 transition-opacity">ACCESS</span>
                                    </div>
                                </div>
                            </a>

                            <a href="https://forge.laravel.com" class="block group">
                                <div class="bg-white/5 border border-white/10 p-4 rounded hover:bg-white/10 transition-all duration-300 group-hover:border-green-500/50">
                                    <div class="flex justify-between items-center">
                                        <span class="font-mono text-sm font-bold text-gray-300 group-hover:text-green-400">>> FORGE DEPLOY</span>
                                        <span class="text-xs text-gray-600 group-hover:text-white opacity-0 group-hover:opacity-100 transition-opacity">INIT</span>
                                    </div>
                                </div>
                            </a>

                            <a href="https://vapor.laravel.com" class="block group">
                                <div class="bg-white/5 border border-white/10 p-4 rounded hover:bg-white/10 transition-all duration-300 group-hover:border-cyan-500/50">
                                    <div class="flex justify-between items-center">
                                        <span class="font-mono text-sm font-bold text-gray-300 group-hover:text-cyan-400">>> VAPOR SERVERLESS</span>
                                        <span class="text-xs text-gray-600 group-hover:text-white opacity-0 group-hover:opacity-100 transition-opacity">CONNECT</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="mt-12 flex justify-between items-end text-[10px] font-mono text-gray-600">
                    <div>
                        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                    </div>
                    <div class="flex items-center gap-2">
                        SYSTEM ONLINE
                        <span class="block w-1.5 h-1.5 bg-green-500 rounded-full shadow-[0_0_5px_#00ff00] animate-pulse"></span>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // --- 1. BOOT SEQUENCE ---
        const bootScreen = document.getElementById('boot-screen');
        const bootBar = document.getElementById('boot-bar');
        const mainInterface = document.getElementById('main-interface');

        window.addEventListener('load', () => {
            // Start boot bar animation
            setTimeout(() => { bootBar.style.width = "100%"; }, 100);

            // Hide boot screen and show main interface
            setTimeout(() => {
                bootScreen.style.opacity = "0";
                bootScreen.style.pointerEvents = "none";
                mainInterface.style.opacity = "1";
                mainInterface.style.transform = "scale(1)";
            }, 2200);
        });

        // --- 2. RGB STAR WARP EFFECT ---
        const canvas = document.getElementById('warp-canvas');
        const ctx = canvas.getContext('2d');
        let width, height, stars = [];
        
        // RGB Cycle variables
        let hue = 0;

        function resize() {
            width = window.innerWidth; height = window.innerHeight;
            canvas.width = width; canvas.height = height;
        }
        window.addEventListener('resize', resize);
        resize();

        // Create Stars
        for(let i = 0; i < 600; i++) {
            stars.push({ x: Math.random() * width - width/2, y: Math.random() * height - height/2, z: Math.random() * width });
        }

        function drawStars() {
            // Fade effect for trails
            ctx.fillStyle = "rgba(5, 5, 5, 0.2)"; 
            ctx.fillRect(0, 0, width, height);
            
            const cx = width / 2; const cy = height / 2;
            
            // Update Hue for RGB effect
            hue = (hue + 0.5) % 360;
            const color = `hsl(${hue}, 100%, 70%)`;

            stars.forEach(star => {
                star.z -= 2; // Speed
                if(star.z <= 0) { star.z = width; star.x = Math.random() * width - width/2; star.y = Math.random() * height - height/2; }
                
                const x = cx + (star.x / star.z) * width;
                const y = cy + (star.y / star.z) * width;
                
                const size = (1 - star.z / width) * 2;
                
                ctx.fillStyle = color;
                ctx.beginPath();
                ctx.arc(x, y, size, 0, Math.PI * 2);
                ctx.fill();
            });
            requestAnimationFrame(drawStars);
        }
        drawStars();

        // --- 3. MAGNETIC CURSOR ---
        const cursor = document.getElementById('cursor');
        const cursorDot = document.getElementById('cursor-dot');
        let mouseX = 0, mouseY = 0, cursorX = 0, cursorY = 0;

        document.addEventListener('mousemove', (e) => {
            mouseX = e.clientX; mouseY = e.clientY;
            cursorDot.style.left = mouseX + 'px'; cursorDot.style.top = mouseY + 'px';
        });

        function animateCursor() {
            cursorX += (mouseX - cursorX) * 0.15; 
            cursorY += (mouseY - cursorY) * 0.15;
            cursor.style.left = cursorX + 'px'; 
            cursor.style.top = cursorY + 'px';
            requestAnimationFrame(animateCursor);
        }
        animateCursor();

        const targets = document.querySelectorAll('a, button');
        targets.forEach(target => {
            target.addEventListener('mouseenter', () => { cursor.classList.add('cursor-hover'); });
            target.addEventListener('mouseleave', () => { cursor.classList.remove('cursor-hover'); });
        });
    </script>
</body>
</html>