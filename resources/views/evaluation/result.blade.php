@extends('layouts.app')

@section('title', 'Hasil Konsensus')
@section('header', 'HASIL KEPUTUSAN KELOMPOK (BORDA)')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>

    {{-- CSS UTAMA (PRINT & SCREEN) --}}
    <style>
        /* === STYLE KHUSUS CETAK (TIDAK DIUBAH - FORMAL) === */
        @media print {
            .no-print, nav, header, aside, .sidebar, .bg-gray-900, button, .main-content > div:first-child, .sci-fi-bg, .scanline-overlay { 
                display: none !important; 
            }
            body, .main-content, .bg-gray-100, .holo-card, .tech-border { 
                background-color: white !important; 
                color: black !important;
                margin: 0; padding: 0;
                border: none !important;
                box-shadow: none !important;
            }
            .text-white, .text-yellow-400, .text-gray-400, .text-cyan-400, .text-amber-500 {
                color: black !important;
            }
            .grid-print { display: block !important; page-break-inside: avoid; }
            .col-print {
                width: 100% !important; margin-bottom: 20px;
                background: white !important; border: 1px solid #ddd !important;
            }
            canvas { max-width: 100% !important; max-height: 400px !important; }
            .print-header {
                display: block !important; text-align: center; margin-bottom: 30px;
                border-bottom: 3px double black; padding-bottom: 10px;
            }
            .signature-section {
                display: flex !important; justify-content: space-between; margin-top: 50px; page-break-inside: avoid;
            }
            table { width: 100% !important; border-collapse: collapse; font-size: 12px; }
            th, td { border: 1px solid black !important; padding: 8px; color: black !important; }
            thead { background-color: #f3f4f6 !important; -webkit-print-color-adjust: exact; }
            .shadow, .rounded-lg, .border-l-4, .border-yellow-500, .border-cyan-500 {
                box-shadow: none !important; border-radius: 0 !important; border: none !important;
            }
            .hud-corner, .scanline, .animate-pulse, .absolute, .tech-accent { display: none !important; }
        }
        .print-header, .signature-section { display: none; }

        /* === STYLE LAYAR (CYBERPUNK YELLOW) === */
        .sci-fi-bg {
            background-image: 
                linear-gradient(rgba(251, 191, 36, 0.03) 1px, transparent 1px), 
                linear-gradient(90deg, rgba(251, 191, 36, 0.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .tech-border {
            position: relative;
            background: rgba(11, 17, 32, 0.85);
            border: 1px solid rgba(245, 158, 11, 0.3);
            backdrop-filter: blur(12px);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
        }
        /* Sudut Siku-Siku HUD */
        .corner-tl { position: absolute; top: -1px; left: -1px; width: 10px; height: 10px; border-top: 2px solid #f59e0b; border-left: 2px solid #f59e0b; }
        .corner-tr { position: absolute; top: -1px; right: -1px; width: 10px; height: 10px; border-top: 2px solid #f59e0b; border-right: 2px solid #f59e0b; }
        .corner-bl { position: absolute; bottom: -1px; left: -1px; width: 10px; height: 10px; border-bottom: 2px solid #f59e0b; border-left: 2px solid #f59e0b; }
        .corner-br { position: absolute; bottom: -1px; right: -1px; width: 10px; height: 10px; border-bottom: 2px solid #f59e0b; border-right: 2px solid #f59e0b; }
        
        /* Animasi Scanline Halus */
        @keyframes scan {
            0% { background-position: 0% 0%; }
            100% { background-position: 0% 100%; }
        }
        .scanline-overlay {
            position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(to bottom, transparent 50%, rgba(245, 158, 11, 0.02) 51%);
            background-size: 100% 4px;
            animation: scan 10s linear infinite;
            z-index: 0;
        }
    </style>

    {{-- BACKGROUND UTAMA --}}
    <div class="sci-fi-bg fixed inset-0 pointer-events-none z-0"></div>
    <div class="fixed inset-0 pointer-events-none z-0 bg-gradient-to-b from-[#05080f] via-transparent to-[#05080f]"></div>

    {{-- HEADER PRINT (HIDDEN ON SCREEN) --}}
    <div class="print-header">
        <h1 class="text-2xl font-bold uppercase tracking-wider">BERITA ACARA KEPUTUSAN</h1>
        <h2 class="text-xl font-bold">PEMILIHAN SUPERVISOR TOKO RETAIL</h2>
        <p class="text-sm mt-2">Dicetak pada Tanggal: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
    </div>

    {{-- HEADER DASHBOARD (LAYAR) --}}
    <div class="tech-border rounded-none mb-8 z-10 no-print overflow-hidden relative group">
        <div class="scanline-overlay"></div>
        <div class="bg-black/40 border-b border-yellow-500/20 p-4 flex items-center justify-between relative z-10">
            <div class="flex items-center gap-4">
                <div class="flex gap-1">
                    <div class="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse"></div>
                    <div class="w-1.5 h-1.5 bg-yellow-500 rounded-full"></div>
                    <div class="w-1.5 h-1.5 bg-green-500 rounded-full"></div>
                </div>
                <h3 class="text-yellow-400 font-mono font-bold text-sm tracking-[0.3em] opacity-90 flex items-center gap-2">
                    <i class="fas fa-satellite-dish text-xs"></i> HASIL KONSENSUS
                </h3>
            </div>
            <div class="text-[10px] font-mono text-yellow-600 bg-yellow-900/20 px-2 py-1 border border-yellow-500/30 rounded">
                SUPERVISOR
            </div>
        </div>
        <div class="h-0.5 w-full bg-gradient-to-r from-transparent via-yellow-500 to-transparent opacity-50"></div>
    </div>

    {{-- ALERT NOTIFICATIONS --}}
    <div class="no-print relative z-10 max-w-7xl mx-auto">
        @if(session('success'))
            <div class="tech-border border-l-4 border-l-green-500 p-4 mb-6 flex items-center gap-4 text-green-400 bg-green-900/20">
                <div class="p-2 bg-green-500/10 rounded-full"><i class="fas fa-check-circle text-xl"></i></div>
                <div>
                    <h4 class="font-bold font-mono text-sm">SUCCESS_PROTOCOL</h4>
                    <p class="text-xs opacity-80">{{ session('success') }}</p>
                </div>
            </div>
        @endif
        @if(session('error'))
            <div class="tech-border border-l-4 border-l-red-500 p-4 mb-6 flex items-center gap-4 text-red-400 bg-red-900/20">
                <div class="p-2 bg-red-500/10 rounded-full"><i class="fas fa-exclamation-triangle text-xl"></i></div>
                <div>
                    <h4 class="font-bold font-mono text-sm">ERROR_PROTOCOL</h4>
                    <p class="text-xs opacity-80">{{ session('error') }}</p>
                </div>
            </div>
        @endif
    </div>

    {{-- ACTION BUTTONS --}}
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center no-print gap-4 relative z-10">
        <button onclick="window.print()" class="group relative px-8 py-3 bg-black border border-gray-600 hover:border-white transition-all duration-300">
            <div class="absolute inset-0 bg-gray-800 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
            <div class="relative flex items-center gap-3 text-gray-300 group-hover:text-white font-mono tracking-widest text-sm">
                <i class="fas fa-print"></i> CETAK LAPORAN PDF
            </div>
            <div class="absolute top-0 left-0 w-1 h-1 bg-white"></div>
            <div class="absolute bottom-0 right-0 w-1 h-1 bg-white"></div>
        </button>

        @if(Auth::user()->role == 'area_manager')
        <div class="flex items-center gap-6">
            <div class="text-[10px] font-mono text-yellow-600/80 text-right uppercase tracking-widest">
                <i class="fas fa-circle text-[6px] text-yellow-500 animate-ping mr-1"></i>
                Menunggu data...
            </div>
            <form action="{{ route('consensus.generate') }}" method="POST" class="inline-block">
                @csrf
                <button type="submit" class="group relative px-8 py-3 bg-yellow-900/20 border border-yellow-500 hover:bg-yellow-500/20 transition-all duration-300">
                    <div class="absolute inset-0 bg-yellow-500/10 blur-lg opacity-0 group-hover:opacity-50 transition-opacity"></div>
                    <div class="relative flex items-center gap-3 text-yellow-400 font-mono font-bold tracking-wider text-sm">
                        <i class="fas fa-sync-alt group-hover:animate-spin"></i> ULANGI...
                    </div>
                    <div class="absolute top-0 right-0 w-2 h-2 border-t border-r border-yellow-400"></div>
                    <div class="absolute bottom-0 left-0 w-2 h-2 border-b border-l border-yellow-400"></div>
                </button>
            </form>
        </div>
        @endif
    </div>

    @if(!$hasResult)
        {{-- EMPTY STATE --}}
        <div class="tech-border p-12 text-center relative z-10 no-print">
            <div class="corner-tl"></div><div class="corner-tr"></div><div class="corner-bl"></div><div class="corner-br"></div>
            <div class="mb-6 inline-block p-6 rounded-full bg-gray-800/50 border border-gray-700">
                <i class="fas fa-database text-5xl text-gray-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-400 font-mono tracking-tighter">DATA KOSONG</h3>
            <p class="text-gray-500 font-mono text-sm mt-2">Sistem menunggu kalkulasi dan arahan dari Area Manager.</p>
        </div>
    @else
        
        <div class="mb-4 flex justify-end items-center gap-2 no-print relative z-10">
             <div class="h-px w-12 bg-yellow-500/30"></div>
             <div class="text-[10px] font-mono text-yellow-500 uppercase tracking-widest">
                Last Update: <span class="text-white">{{ $lastRun->format('d M Y // H:i:s') }}</span>
             </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 grid-print relative z-10">
            
            {{-- 1. CHART SECTION --}}
            <div class="tech-border p-6 col-print relative group">
                <div class="corner-tl"></div><div class="corner-tr"></div><div class="corner-bl"></div><div class="corner-br"></div>
                
                <div class="flex justify-between items-end mb-6 border-b border-yellow-500/20 pb-2">
                    <h3 class="font-bold font-mono text-yellow-400 text-lg flex items-center gap-2">
                        <i class="fas fa-chart-bar text-sm"></i> VISUAL_METRICS
                    </h3>
                    <div class="flex gap-1">
                        <div class="w-1 h-3 bg-yellow-500/50"></div>
                        <div class="w-1 h-5 bg-yellow-500/80"></div>
                        <div class="w-1 h-2 bg-yellow-500/30"></div>
                    </div>
                </div>
                
                <div class="h-64 w-full relative">
                    <canvas id="bordaChart"></canvas>
                </div>
            </div>

            {{-- 2. TOP CANDIDATE SECTION --}}
            <div class="tech-border p-6 col-print relative flex flex-col justify-center items-center bg-gradient-to-b from-[#0B1120] to-[#1a1600]">
                <div class="corner-tl"></div><div class="corner-tr"></div><div class="corner-bl"></div><div class="corner-br"></div>
                <div class="scanline-overlay opacity-30"></div>
                
                {{-- Glow Effect Behind Trophy --}}
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 bg-yellow-500/20 blur-[50px] rounded-full no-print"></div>

                <div class="relative z-10 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 mb-4 rounded-lg border border-yellow-500/50 bg-yellow-500/10 shadow-[0_0_20px_rgba(234,179,8,0.2)] no-print">
                        <i class="fas fa-trophy text-4xl text-yellow-400 drop-shadow-[0_0_5px_rgba(234,179,8,0.8)]"></i>
                    </div>
                    
                    <h2 class="text-yellow-600 font-mono font-bold uppercase tracking-[0.3em] text-[10px] mb-2">TOP RECRUIT IDENTIFIED</h2>
                    @if(isset($results[0]))
                        <h1 class="text-4xl md:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-b from-white to-yellow-200 mb-4 tracking-tighter">
                            {{ $results[0]->candidate->name }}
                        </h1>
                        
                        <div class="grid grid-cols-2 gap-3 w-full max-w-xs mx-auto font-mono text-xs">
                            <div class="bg-black/50 border border-yellow-500/30 p-2 text-center">
                                <span class="block text-gray-500 text-[9px] uppercase">Total Score</span>
                                <span class="text-xl font-bold text-yellow-400">{{ $results[0]->total_points }}</span>
                            </div>
                            <div class="bg-black/50 border border-yellow-500/30 p-2 text-center">
                                <span class="block text-gray-500 text-[9px] uppercase">Rank Status</span>
                                <span class="text-xl font-bold text-yellow-400">#1</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- 3. RANKING TABLE --}}
        <div class="tech-border mb-10 overflow-hidden relative z-10">
            <div class="corner-tl"></div><div class="corner-tr"></div><div class="corner-bl"></div><div class="corner-br"></div>
            
            <div class="bg-black/50 border-b border-yellow-500/20 p-4 flex justify-between items-center no-print">
                <h3 class="font-bold font-mono text-yellow-400 text-sm tracking-widest flex items-center gap-2">
                    <i class="fas fa-list text-yellow-600"></i> RANKING_MATRIX
                </h3>
                <div class="text-[10px] font-mono text-gray-500">SORT: DESCENDING</div>
            </div>

            <div class="print-header hidden text-left font-bold mb-2 mt-6" style="border:none; margin-bottom:10px;">DETAIL PERINGKAT FINAL:</div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-yellow-500/5 text-yellow-600 font-mono uppercase text-[10px] tracking-wider">
                            <th class="py-3 px-6 text-center w-24 border-b border-yellow-500/20">#Rank</th>
                            <th class="py-3 px-6 border-b border-yellow-500/20">Candidate Name</th>
                            <th class="py-3 px-6 text-center border-b border-yellow-500/20">Total Points</th>
                            <th class="py-3 px-6 text-center border-b border-yellow-500/20">Decision</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-300 text-sm font-mono">
                        @foreach($results as $res)
                        <tr class="border-b border-yellow-500/10 hover:bg-yellow-500/10 transition-colors group {{ $res->final_rank == 1 ? 'bg-yellow-500/5' : '' }}">
                            <td class="py-4 px-6 text-center">
                                @if($res->final_rank == 1) 
                                    <div class="inline-flex items-center justify-center w-6 h-6 rounded bg-yellow-500 text-black font-bold shadow-[0_0_10px_rgba(234,179,8,0.5)]">1</div>
                                @else 
                                    <span class="opacity-50">{{ $res->final_rank }}</span> 
                                @endif
                            </td>
                            <td class="py-4 px-6 font-bold text-white group-hover:text-yellow-300 transition-colors tracking-wide">
                                {{ $res->candidate->name }}
                            </td>
                            <td class="py-4 px-6 text-center text-yellow-500 font-bold text-lg">
                                {{ $res->total_points }}
                            </td>
                            <td class="py-4 px-6 text-center">
                                @if($res->final_rank <= 3) 
                                    <span class="inline-block px-3 py-1 text-[10px] font-bold text-black bg-green-500 rounded shadow-[0_0_10px_rgba(34,197,94,0.5)]">RECOMMENDED</span> 
                                @else 
                                    <span class="text-gray-600 text-xs">-</span> 
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. SIGNATURE SECTION (PRINT ONLY) --}}
        <div class="signature-section w-full flex justify-between text-center px-10 text-sm mt-12">
            <div class="w-1/3">
                <p>Mengetahui,</p>
                <p class="font-bold mb-20">HR Department</p>
                <p>( ................................. )</p>
            </div>
            <div class="w-1/3">
                <p>Menyetujui,</p>
                <p class="font-bold mb-20">Area Manager</p>
                <p class="font-bold underline">{{ Auth::user()->role == 'area_manager' ? Auth::user()->name : '( ................................. )' }}</p>
            </div>
        </div>

        {{-- 5. DEBUG / TRANSPARENCY (SCREEN ONLY) --}}
        <div x-data="{ open: false }" class="tech-border mb-10 no-print relative z-10">
            <div class="corner-tl"></div><div class="corner-tr"></div><div class="corner-bl"></div><div class="corner-br"></div>
            
            <button @click="open = !open" class="w-full flex justify-between items-center p-4 hover:bg-white/5 focus:outline-none transition-colors border-b border-yellow-500/10">
                <h3 class="font-bold text-gray-400 font-mono text-xs flex items-center tracking-widest">
                    <i class="fas fa-code mr-2 text-yellow-600"></i> 
                    SYSTEM_LOGS // CALCULATION_TRANSPARENCY
                </h3>
                <i :class="open ? 'fa-minus text-yellow-500' : 'fa-plus text-gray-600'" class="fas text-xs"></i>
            </button>

            <div x-show="open" class="p-6 space-y-8 bg-black/40" style="display: none;">
                
                {{-- 5A. BORDA TABLE --}}
                <div>
                    <h4 class="text-yellow-500 mb-3 text-xs font-mono uppercase border-l-2 border-yellow-500 pl-2">A. Aggregation Source (Borda)</h4>
                    <div class="overflow-x-auto border border-gray-800">
                        <table class="w-full table-auto text-xs font-mono text-center">
                            <thead class="bg-gray-900 text-gray-400">
                                <tr>
                                    <th class="p-2 text-left">CANDIDATE</th>
                                    @foreach(\App\Models\User::where('role','!=','admin')->get() as $u)
                                        <th class="p-2 border-l border-gray-800">{{ $u->name }}<br><span class="text-[9px] text-gray-600">{{ strtoupper($u->role) }}</span></th>
                                    @endforeach
                                    <th class="p-2 bg-yellow-900/20 text-yellow-500 border-l border-gray-800">TOTAL</th>
                                </tr>
                            </thead>
                            <tbody class="bg-black/20 text-gray-300">
                                @foreach($candidates as $can)
                                <tr class="border-t border-gray-800 hover:bg-white/5">
                                    <td class="p-2 text-left font-bold text-white">{{ $can->name }}</td>
                                    @foreach(\App\Models\User::where('role','!=','admin')->get() as $u)
                                        @php 
                                            $rankData = $topsisBreakdown[$can->id]->where('user_id', $u->id)->first();
                                            $rank = $rankData ? $rankData->rank : '-';
                                            $poin = $rank != '-' ? (count($candidates) - $rank + 1) : 0;
                                        @endphp
                                        <td class="p-2 border-l border-gray-800">
                                            R:<span class="text-white">{{ $rank }}</span> <span class="text-yellow-600">({{ $poin }}pts)</span>
                                        </td>
                                    @endforeach
                                    <td class="p-2 font-bold text-yellow-400 bg-yellow-500/5 border-l border-gray-800">{{ $results->where('candidate_id', $can->id)->first()->total_points ?? 0 }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 5B. TOPSIS STEPS --}}
                <div>
                    <h4 class="text-yellow-500 mb-3 text-xs font-mono uppercase border-l-2 border-yellow-500 pl-2">B. Calculation Step (User: {{ Auth::user()->name }})</h4>
                    
                    <div x-data="{ tab: 'x' }">
                        <div class="flex gap-1 mb-2">
                            <button @click="tab = 'x'" :class="tab === 'x' ? 'bg-yellow-600 text-black' : 'bg-gray-800 text-gray-500 hover:bg-gray-700'" class="px-3 py-1 text-[10px] font-mono font-bold transition">1. MATRIX(X)</button>
                            <button @click="tab = 'r'" :class="tab === 'r' ? 'bg-yellow-600 text-black' : 'bg-gray-800 text-gray-500 hover:bg-gray-700'" class="px-3 py-1 text-[10px] font-mono font-bold transition">2. NORMAL(R)</button>
                            <button @click="tab = 'y'" :class="tab === 'y' ? 'bg-yellow-600 text-black' : 'bg-gray-800 text-gray-500 hover:bg-gray-700'" class="px-3 py-1 text-[10px] font-mono font-bold transition">3. WEIGHT(Y)</button>
                        </div>

                        <div class="border border-gray-800 bg-black/20 p-2 overflow-x-auto">
                            {{-- TAB X --}}
                            <div x-show="tab === 'x'">
                                <table class="w-full text-xs font-mono text-center text-gray-400">
                                    <thead><tr><th class="p-1 text-left">ALT</th>@foreach($criterias as $c)<th class="p-1 text-yellow-600">{{ $c->code }}</th>@endforeach</tr></thead>
                                    <tbody>@foreach($candidates as $can)<tr><td class="p-1 text-left text-white">{{ $can->name }}</td>@foreach($criterias as $c)<td class="p-1">{{ $matrixX[$can->id][$c->id] ?? '-' }}</td>@endforeach</tr>@endforeach</tbody>
                                </table>
                            </div>
                            {{-- TAB R --}}
                            <div x-show="tab === 'r'" style="display: none;">
                                <table class="w-full text-xs font-mono text-center text-gray-400">
                                    <thead><tr><th class="p-1 text-left">ALT</th>@foreach($criterias as $c)<th class="p-1 text-yellow-600">{{ $c->code }}</th>@endforeach</tr></thead>
                                    <tbody>@foreach($candidates as $can)<tr><td class="p-1 text-left text-white">{{ $can->name }}</td>@foreach($criterias as $c)<td class="p-1">{{ number_format($matrixR[$can->id][$c->id] ?? 0, 4) }}</td>@endforeach</tr>@endforeach</tbody>
                                </table>
                            </div>
                            {{-- TAB Y --}}
                            <div x-show="tab === 'y'" style="display: none;">
                                <table class="w-full text-xs font-mono text-center text-gray-400">
                                    <thead><tr><th class="p-1 text-left">ALT</th>@foreach($criterias as $c)<th class="p-1 text-yellow-600">{{ $c->code }}</th>@endforeach</tr></thead>
                                    <tbody>@foreach($candidates as $can)<tr><td class="p-1 text-left text-white">{{ $can->name }}</td>@foreach($criterias as $c)<td class="p-1 text-green-400">{{ number_format($matrixY[$can->id][$c->id] ?? 0, 4) }}</td>@endforeach</tr>@endforeach</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <script>
            const ctx = document.getElementById('bordaChart');
            const labels = {!! json_encode($results->pluck('candidate.name')) !!};
            const data = {!! json_encode($results->pluck('total_points')) !!};

            // Custom Gradient for Chart Bars
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(234, 179, 8, 0.8)');
            gradient.addColorStop(1, 'rgba(234, 179, 8, 0.1)');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Total Poin',
                        data: data,
                        backgroundColor: gradient,
                        borderColor: '#facc15',
                        borderWidth: 1,
                        barThickness: 40,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { color: '#9ca3af', font: { family: 'monospace' } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { color: '#d1d5db', font: { family: 'monospace' } }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        </script>

    @endif
@endsection