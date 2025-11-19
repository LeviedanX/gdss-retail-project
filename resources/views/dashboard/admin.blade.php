<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ADMIN // NEXUS')</title>
    
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
                        'glass': 'rgba(255, 255, 255, 0.03)',
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
            --primary: #d946ef; /* NEON PURPLE */
            --secondary: #00e5ff; /* CYAN */
        }

        body {
            background-color: #05000a;
            margin: 0;
            color: white;
            cursor: none; /* Menyembunyikan cursor asli */
            overflow-x: hidden;
        }

        /* --- SCROLLBAR --- */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #0a050f; }
        ::-webkit-scrollbar-thumb { background: var(--primary); border-radius: 4px; }

        /* --- CRT EFFECTS --- */
        .scanlines {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: linear-gradient(to bottom, rgba(255,255,255,0), rgba(255,255,255,0) 50%, rgba(0,0,0,0.2) 50%, rgba(0,0,0,0.2));
            background-size: 100% 4px; z-index: 50; pointer-events: none; opacity: 0.3;
        }
        .vignette {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            background: radial-gradient(circle, rgba(0,0,0,0) 60%, rgba(5,0,10,1) 100%);
            z-index: 51; pointer-events: none;
        }

        /* --- BOOT SCREEN --- */
        #boot-screen {
            position: fixed; inset: 0; background: #05000a; z-index: 9999;
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            font-family: 'Space Mono', monospace;
        }
        .boot-title-glitch {
            font-size: 4rem; font-weight: 800; position: relative; color: white; letter-spacing: -2px;
        }
        .boot-title-glitch::before, .boot-title-glitch::after {
            content: attr(data-text); position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: #05000a;
        }
        .boot-title-glitch::before { left: 2px; text-shadow: -1px 0 var(--secondary); animation: glitch 2s infinite; }
        .boot-title-glitch::after { left: -2px; text-shadow: -1px 0 var(--primary); animation: glitch 3s infinite; }

        /* --- HOLO CARDS --- */
        .holo-card {
            background: rgba(20, 5, 30, 0.4);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(217, 70, 239, 0.2);
            box-shadow: 0 0 20px rgba(217, 70, 239, 0.05);
            position: relative; overflow: hidden;
            transition: all 0.3s ease;
        }
        .holo-card:hover {
            border-color: var(--primary);
            box-shadow: 0 0 30px rgba(217, 70, 239, 0.15);
        }

        /* --- TECH INPUTS & BUTTONS --- */
        .tech-input {
            background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.1);
            transition: all 0.3s ease; color: #fff; font-family: 'Space Mono', monospace;
        }
        .tech-input:focus {
            border-color: var(--primary); box-shadow: 0 0 15px rgba(217, 70, 239, 0.3);
            background: rgba(217, 70, 239, 0.05); outline: none;
        }
        
        .tech-btn {
            background: transparent; border: 1px solid var(--primary); color: var(--primary);
            font-family: 'Space Mono', monospace; letter-spacing: 2px; text-transform: uppercase;
            position: relative; overflow: hidden; transition: all 0.3s;
        }
        .tech-btn:hover {
            background: var(--primary); color: black; box-shadow: 0 0 20px var(--primary);
        }
        .tech-btn-danger {
            border-color: #ef4444; color: #ef4444;
        }
        .tech-btn-danger:hover {
            background: #ef4444; color: white; box-shadow: 0 0 20px #ef4444;
        }

        /* --- TABLES --- */
        .tech-table th {
            text-align: left; padding: 1rem;
            border-bottom: 1px solid rgba(217, 70, 239, 0.3);
            color: var(--secondary); font-family: 'Space Mono', monospace; font-size: 0.8rem;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .tech-table td {
            padding: 1rem; border-bottom: 1px solid rgba(255,255,255,0.05);
            font-family: 'Outfit', sans-serif; color: rgba(255,255,255,0.8);
        }
        .tech-table tr:hover td {
            background: rgba(217, 70, 239, 0.05); color: white;
        }

        /* --- CURSOR (REVISI Z-INDEX) --- */
        #cursor {
            position: fixed; top: 0; left: 0; width: 20px; height: 20px;
            border: 1px solid var(--primary); border-radius: 50%;
            transform: translate(-50%, -50%); pointer-events: none; 
            /* Z-INDEX DINAIKKAN MENJADI 99999 AGAR DI ATAS MODAL (YANG 10000) */
            z-index: 99999;
            transition: width 0.2s, height 0.2s; mix-blend-mode: difference;
        }
        #cursor-dot {
            position: fixed; top: 0; left: 0; width: 4px; height: 4px;
            background: var(--primary); border-radius: 50%; transform: translate(-50%, -50%);
            pointer-events: none; 
            /* Z-INDEX DINAIKKAN MENJADI 99999 AGAR DI ATAS MODAL */
            z-index: 99999;
        }
        .cursor-hover {
            width: 50px !important; height: 50px !important; background-color: rgba(217, 70, 239, 0.2);
        }
    </style>
</head>
<body class="font-sans selection:bg-purple-neon selection:text-white">

    <div class="scanlines"></div>
    <div class="vignette"></div>
    <canvas id="warp-canvas" class="fixed inset-0 z-0"></canvas>
    
    <div id="cursor"></div>
    <div id="cursor-dot"></div>

    <div id="boot-screen">
        <div class="boot-title-glitch" data-text="SYSTEM ADMIN">SYSTEM ADMIN</div>
        <div class="text-purple-neon font-mono text-xs mt-4 tracking-[0.5em] animate-pulse">ESTABLISHING CONNECTION...</div>
        <div class="w-64 h-1 bg-gray-900 mt-6 relative overflow-hidden rounded">
            <div id="boot-bar" class="absolute top-0 left-0 h-full bg-purple-neon shadow-[0_0_10px_#d946ef] w-0 transition-all duration-[2000ms] ease-out"></div>
        </div>
    </div>

    <div id="main-interface" class="relative z-10 min-h-screen p-6 md:p-12 opacity-0 transition-opacity duration-1000">
        
        <header class="mb-12 flex justify-between items-end border-b border-white/10 pb-6">
            <div>
                <h1 class="text-4xl font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-white to-purple-neon font-mono">
                    SELAMAT DATANG, ADMIN!
                </h1>
                <p class="text-xs font-mono text-cyan-neon mt-2 tracking-widest">ADMIN DASHBOARD</p>
            </div>

            <div class="flex items-end gap-6">
                <div class="text-right hidden md:block">
                    <div class="text-[10px] text-gray-500 font-mono">SYSTEM TIME</div>
                    <div id="clock" class="text-xl font-mono text-white">00:00:00</div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>

                <button onclick="toggleShutdown()" type="button" class="group relative px-4 py-2 border border-red-500/50 text-red-500 hover:bg-red-500/10 hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] transition-all rounded overflow-hidden">
                    <span class="relative z-10 font-mono text-xs font-bold tracking-widest flex items-center gap-2">
                        <i class="fas fa-power-off"></i> LOGOUT
                    </span>
                    <div class="absolute inset-0 bg-red-500/20 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
                </button>
            </div>
        </header>

        @if(session('success'))
        <div class="mb-8 holo-card border-l-4 !border-l-green-500 p-4 flex items-center gap-4 animate-pulse">
            <i class="fas fa-check-circle text-green-500 text-xl"></i>
            <div>
                <h4 class="font-mono text-green-500 text-xs uppercase tracking-widest">Success</h4>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <div class="holo-card rounded-xl mb-12">
            <div class="p-4 border-b border-white/5 bg-purple-900/20 flex justify-between items-center">
                <h3 class="font-bold font-mono text-purple-neon flex items-center gap-2">
                    <i class="fas fa-users-cog"></i> MANAJEMEN USER
                </h3>
                <div class="h-1 w-24 bg-purple-neon shadow-[0_0_10px_#d946ef]"></div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full tech-table text-sm">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="font-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <span class="border border-white/20 px-2 py-1 rounded text-[10px] font-mono uppercase text-cyan-neon">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="text-right">
                                <form action="{{ route('admin.changePassword', $user->id) }}" method="POST" class="flex items-center justify-end gap-2">
                                    @csrf
                                    @method('PUT')
                                    
                                    <input type="text" name="password" placeholder="New Pass..." 
                                           class="bg-transparent border-b border-white/10 w-24 text-xs focus:border-purple-neon focus:outline-none py-1 text-right text-gray-400 focus:text-white font-mono transition-colors"
                                           required minlength="6">
                                    
                                    <button type="submit" class="text-xs hover:text-yellow-400 text-yellow-600 transition-colors font-mono" title="Simpan Password">
                                        [ SAVE ]
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="holo-card rounded-xl flex flex-col">
                <div class="p-4 border-b border-white/5 bg-blue-900/20 flex justify-between items-center">
                    <h3 class="font-bold font-mono text-cyan-neon flex items-center gap-2">
                        <i class="fas fa-user-tie"></i> DATA KANDIDAT
                    </h3>
                    <div class="text-[10px] font-mono text-gray-500">CANDIDATES</div>
                </div>
                
                <div class="p-6 flex-1">
                    
                    <form action="{{ route('admin.candidate.store') }}" method="POST" class="mb-8 grid grid-cols-4 gap-4 items-end">
                        @csrf
                        
                        <div class="col-span-2">
                            <label class="text-[10px] font-mono text-cyan-neon block mb-2 tracking-widest">NAMA KANDIDAT</label>
                            <input type="text" name="name" placeholder="NAMA LENGKAP" class="tech-input w-full px-3 py-2 rounded text-sm uppercase" required>
                        </div>
                        
                        <div>
                            <label class="text-[10px] font-mono text-cyan-neon block mb-2 tracking-widest">UMUR</label>
                            <input type="number" name="age" placeholder="25" class="tech-input w-full px-3 py-2 rounded text-sm text-center" required>
                        </div>
                        
                        <div class="flex gap-2">
                            <div class="flex-1">
                                <label class="text-[10px] font-mono text-cyan-neon block mb-2 tracking-widest">EXP (THN)</label>
                                <input type="number" name="experience_year" placeholder="3" class="tech-input w-full px-3 py-2 rounded text-sm text-center" required>
                            </div>
                            <button type="submit" class="tech-btn px-4 py-2 rounded text-xs h-[38px] self-end mb-[1px] hover:bg-cyan-neon hover:text-black hover:shadow-[0_0_15px_#00e5ff] border-cyan-neon text-cyan-neon">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </form>

                    <div class="overflow-y-auto max-h-[300px] pr-2">
                        <table class="w-full tech-table text-sm">
                            <thead>
                                <tr>
                                    <th class="!text-cyan-neon">Nama</th>
                                    <th class="!text-cyan-neon text-center">Umur</th>
                                    <th class="!text-cyan-neon text-center">Exp</th>
                                    <th class="text-right !text-cyan-neon">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($candidates as $cand)
                                <tr>
                                    <form action="{{ route('admin.candidate.update', $cand->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        
                                        <td class="p-2">
                                            <input type="text" name="name" value="{{ $cand->name }}" 
                                                oninput="this.value = this.value.toUpperCase()"
                                                class="bg-transparent border-none text-white w-full focus:ring-0 focus:text-cyan-neon font-mono text-xs transition-colors uppercase">
                                        </td>

                                        <td class="p-2">
                                            <input type="number" name="age" value="{{ $cand->age }}" 
                                                class="bg-transparent border-none text-gray-400 w-full focus:ring-0 focus:text-cyan-neon font-mono text-xs text-center transition-colors"
                                                placeholder="0">
                                        </td>

                                        <td class="p-2">
                                            <input type="number" name="experience_year" value="{{ $cand->experience_year }}" 
                                                class="bg-transparent border-none text-gray-400 w-full focus:ring-0 focus:text-cyan-neon font-mono text-xs text-center transition-colors"
                                                placeholder="0">
                                        </td>

                                        <td class="p-2 text-right flex justify-end gap-4 items-center">
                                            <button type="submit" class="text-cyan-500 hover:text-cyan-300 transition-colors" title="Simpan Perubahan">
                                                <i class="fas fa-save"></i>
                                            </button>
                                    </form> 
                                            <form action="{{ route('admin.candidate.delete', $cand->id) }}" method="POST" onsubmit="return confirm('Hapus kandidat ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 transition-colors" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="holo-card rounded-xl flex flex-col">
                <div class="p-4 border-b border-white/5 bg-purple-900/20 flex justify-between items-center">
                    <h3 class="font-bold font-mono text-purple-neon flex items-center gap-2">
                        <i class="fas fa-list-check"></i> DATA KRITERIA
                    </h3>
                    <div class="text-[10px] font-mono text-gray-500">CRITERIAS</div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('admin.criteria.store') }}" method="POST" class="mb-8 grid grid-cols-4 gap-2">
                        @csrf
                        <input type="text" name="code" placeholder="C9" class="tech-input px-3 py-2 rounded text-sm text-center" required>
                        <input type="text" name="name" placeholder="NAMA KRITERIA" class="tech-input col-span-2 px-3 py-2 rounded text-sm" required>
                        <select name="type" class="tech-input px-3 py-2 rounded text-sm bg-black">
                            <option value="benefit">BENEFIT</option>
                            <option value="cost">COST</option>
                        </select>
                        <input type="number" step="0.01" name="weight" placeholder="BOBOT (0.1)" class="tech-input col-span-3 px-3 py-2 rounded text-sm" required>
                        <button type="submit" class="tech-btn px-4 py-2 rounded text-xs flex justify-center items-center">
                            <i class="fas fa-plus"></i>
                        </button>
                    </form>

                    <div class="overflow-y-auto max-h-[300px] pr-2">
                        <table class="w-full tech-table text-sm">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Bobot</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($criterias as $crit)
                                <tr>
                                    <form action="{{ route('admin.criteria.update', $crit->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <td class="font-mono font-bold text-purple-400">{{ $crit->code }}</td>
                                        <td><input type="text" name="name" value="{{ $crit->name }}" class="bg-transparent border-b border-white/10 w-full text-xs focus:border-purple-neon focus:outline-none py-1"></td>
                                        <td><input type="number" step="0.01" name="weight" value="{{ $crit->weight }}" class="bg-transparent border-b border-white/10 w-12 text-xs focus:border-purple-neon focus:outline-none py-1 text-center"></td>
                                        <td class="text-right flex justify-end gap-3 items-center pt-3">
                                            <button type="submit" class="text-purple-400 hover:text-white transition-colors"><i class="fas fa-save"></i></button>
                                    </form>
                                            <form action="{{ route('admin.criteria.delete', $crit->id) }}" method="POST" onsubmit="return confirm('Hapus kriteria?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-400 transition-colors"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <footer class="mt-12 border-t border-white/5 pt-6 flex justify-between text-[10px] font-mono text-gray-600 tracking-widest uppercase">
            <div>GDSS PROJECT 2025</div>
            <div>ADMIN</div>
        </footer>

    </div>

    <div id="shutdown-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center backdrop-blur-sm bg-black/80 transition-opacity duration-300 opacity-0">
        
        <div class="holo-card border border-red-500/50 shadow-[0_0_50px_rgba(239,68,68,0.2)] p-1 max-w-sm w-full transform scale-90 transition-transform duration-300" id="modal-box">
            <div class="bg-[#0a0505]/90 p-6 relative overflow-hidden">
                
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-red-500 to-transparent"></div>
                <div class="absolute top-0 left-0 w-full h-full bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10"></div>

                <div class="text-center mb-6 relative z-10">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-500 animate-pulse mb-4"></i>
                    <h3 class="font-mono text-2xl font-bold text-white tracking-tighter">PEMBERITAHUAN</h3>
                    <p class="font-mono text-[10px] text-red-400 tracking-[0.3em] mt-1">Tetap ingin logout?</p>
                </div>

                <div class="flex gap-3 relative z-10">
                    <button onclick="toggleShutdown()" class="flex-1 py-3 border border-gray-600 text-gray-400 font-mono text-xs hover:bg-gray-800 hover:text-white transition-colors">
                        BATAL
                    </button>
                    
                    <button onclick="confirmLogout()" class="flex-1 py-3 bg-red-600 text-black font-bold font-mono text-xs hover:bg-red-500 hover:shadow-[0_0_20px_rgba(220,38,38,0.6)] transition-all tracking-wider">
                        LOGOUT
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script>
        // --- 1. BOOT SEQUENCE ---
        window.addEventListener('load', () => {
            const bootBar = document.getElementById('boot-bar');
            const bootScreen = document.getElementById('boot-screen');
            const mainInterface = document.getElementById('main-interface');

            setTimeout(() => { bootBar.style.width = "100%"; }, 100);

            setTimeout(() => {
                bootScreen.style.opacity = "0";
                setTimeout(() => { bootScreen.style.display = "none"; }, 500);
                mainInterface.style.opacity = "1";
            }, 2200);
        });

        // --- 2. CLOCK ---
        setInterval(() => {
            const now = new Date();
            document.getElementById('clock').innerText = now.toLocaleTimeString('en-GB');
        }, 1000);

        // --- 3. WARP DRIVE PARTICLES (PURPLE THEME) ---
        const canvas = document.getElementById('warp-canvas');
        const ctx = canvas.getContext('2d');
        let width, height, stars = [], warpSpeed = 0.5;

        function resize() {
            width = window.innerWidth; height = window.innerHeight;
            canvas.width = width; canvas.height = height;
        }
        window.addEventListener('resize', resize);
        resize();

        for(let i = 0; i < 600; i++) {
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
                const size = (1 - star.z / width) * 2;
                const alpha = (1 - star.z / width);
                
                ctx.fillStyle = `rgba(217, 70, 239, ${alpha})`;
                
                ctx.beginPath();
                ctx.arc(x, y, size, 0, Math.PI * 2);
                ctx.fill();
            });
            requestAnimationFrame(drawStars);
        }
        drawStars();

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

        const targets = document.querySelectorAll('button, input, a, select');
        targets.forEach(target => {
            target.addEventListener('mouseenter', () => { cursor.classList.add('cursor-hover'); });
            target.addEventListener('mouseleave', () => { cursor.classList.remove('cursor-hover'); });
        });

        // --- 5. FUNGSI MODAL SHUTDOWN ---
        const modal = document.getElementById('shutdown-modal');
        const modalBox = document.getElementById('modal-box');

        function toggleShutdown() {
            if (modal.classList.contains('hidden')) {
                // Buka Modal
                modal.classList.remove('hidden');
                setTimeout(() => {
                    modal.classList.remove('opacity-0');
                    modal.classList.add('flex');
                    modalBox.classList.remove('scale-90');
                    modalBox.classList.add('scale-100');
                }, 10);
            } else {
                // Tutup Modal
                modal.classList.add('opacity-0');
                modalBox.classList.remove('scale-100');
                modalBox.classList.add('scale-90');
                setTimeout(() => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }, 300);
            }
        }

        function confirmLogout() {
            // Animasi visual sebelum submit
            const btn = event.currentTarget;
            btn.innerHTML = "DISCONNECTING...";
            
            // Efek layar mati
            document.body.style.filter = "brightness(0) blur(10px)";
            document.body.style.transition = "all 0.5s";

            // Submit form logout
            setTimeout(() => {
                document.getElementById('logout-form').submit();
            }, 800);
        }

        // Tutup modal jika klik di luar area box
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                toggleShutdown();
            }
        });
    </script>
</body>
</html>