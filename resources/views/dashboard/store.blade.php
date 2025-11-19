@extends('layouts.app')

@section('title', 'Dashboard Kepala Toko')
@section('header', 'PANEL KEPALA TOKO')

@section('content')

    {{-- GLOBAL BACKGROUND GRID EFFECT (RED THEME) --}}
    <div class="fixed inset-0 pointer-events-none z-0" 
         style="background-image: linear-gradient(rgba(239, 68, 68, 0.05) 1px, transparent 1px), 
         linear-gradient(90deg, rgba(239, 68, 68, 0.05) 1px, transparent 1px); 
         background-size: 30px 30px; mask-image: radial-gradient(circle, black 60%, transparent 100%);">
    </div>

    {{-- 1. SECTION ATAS: WELCOME PANEL / SYSTEM PROTOCOL --}}
    <div class="holo-card rounded-xl overflow-hidden relative border border-red-500/20 bg-[#0B1120]/90 backdrop-blur-md shadow-[0_0_30px_rgba(0,0,0,0.5)] mb-8 z-10">
        
        {{-- Header Terminal --}}
        <div class="bg-black/40 border-b border-red-500/10 p-3 px-6 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="flex gap-1.5">
                    <div class="w-2.5 h-2.5 bg-red-500/80 rounded-full shadow-[0_0_5px_rgba(239,68,68,0.5)]"></div>
                    <div class="w-2.5 h-2.5 bg-orange-500/80 rounded-full shadow-[0_0_5px_rgba(249,115,22,0.5)]"></div>
                    <div class="w-2.5 h-2.5 bg-stone-500/80 rounded-full shadow-[0_0_5px_rgba(120,113,108,0.5)]"></div>
                </div>
                <h3 class="text-red-400 font-mono font-bold text-xs tracking-[0.2em] ml-4 opacity-80">
                    DASHBOARD
                </h3>
            </div>
            <div class="text-[10px] font-mono text-red-500/50 animate-pulse">
                ONLINE
            </div>
        </div>

        <div class="p-6 md:p-10 relative">
            {{-- Decorative Scanline --}}
            <div class="absolute inset-0 bg-gradient-to-b from-transparent via-red-500/5 to-transparent opacity-30 pointer-events-none animate-[scan_4s_ease-in-out_infinite]"></div>
            
            <div class="relative z-10">
                <h2 class="text-4xl md:text-5xl font-black text-white mb-4 font-sans tracking-tighter">
                    HALO, <span class="text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-rose-500 to-orange-500 drop-shadow-[0_0_10px_rgba(239,68,68,0.5)]">{{ strtoupper(Auth::user()->name) }}</span>
                </h2>
                
                <div class="w-full h-px bg-gradient-to-r from-red-500/50 via-orange-500/20 to-transparent my-6"></div>

                <div class="grid md:grid-cols-3 gap-8">
                    {{-- Kolom Kiri: Deskripsi --}}
                    <div class="md:col-span-2">
                        <p class="text-gray-400 mb-6 font-mono text-xs md:text-sm leading-relaxed">
                            <span class="text-red-500 mr-2">>></span> OTORITAS: <strong class="text-white">KEPALA TOKO</strong><br>
                            <span class="text-red-500 mr-2">>></span> FOKUS PENILAIAN: <strong class="text-rose-400">PERFORMA & KONDISI LAPANGAN</strong><br>
                            <span class="text-red-500 mr-2">>></span> STATUS: MENUNGGU OBSERVASI OBJEKTIF.
                        </p>

                        {{-- Tombol dihapus di sini --}}
                    </div>

                    {{-- Kolom Kanan: Icon Box --}}
                    <div class="hidden md:flex justify-end items-center opacity-80">
                        <div class="relative">
                            <div class="absolute inset-0 bg-red-500 blur-3xl opacity-20 animate-pulse"></div>
                            <i class="fas fa-store text-8xl text-gradient bg-clip-text text-transparent bg-gradient-to-b from-red-500 to-transparent opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. SECTION BAWAH: STATS GRID --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 relative z-10">
        
        {{-- CARD 1: PERAN ANDA --}}
        <div class="holo-card rounded-lg p-6 border border-red-500/30 bg-[#0f172a]/80 backdrop-blur-sm relative group overflow-hidden hover:-translate-y-1 hover:shadow-[0_0_20px_rgba(239,68,68,0.2)] transition-all duration-300">
            {{-- HUD Corners --}}
            <div class="absolute top-0 left-0 w-3 h-3 border-t-2 border-l-2 border-red-500"></div>
            <div class="absolute bottom-0 right-0 w-3 h-3 border-b-2 border-r-2 border-red-500"></div>
            
            <div class="absolute -right-8 -top-8 w-24 h-24 bg-red-500/10 rounded-full blur-2xl group-hover:bg-red-500/20 transition-all duration-500"></div>
            
            <div class="flex flex-col h-full justify-between relative z-10">
                <div class="flex items-start justify-between mb-4">
                     <div class="h-12 w-12 rounded border border-red-500/30 flex items-center justify-center text-red-500 bg-black/30 shadow-[0_0_15px_rgba(239,68,68,0.1)]">
                        <i class="fas fa-store text-xl"></i>
                    </div>
                    <span class="text-[10px] font-mono text-red-500/50">ROLE</span>
                </div>
                
                <div>
                    <p class="font-mono text-[10px] text-red-400 tracking-[0.2em] uppercase mb-1">Peran Anda</p>
                    <p class="text-3xl font-bold font-mono text-white tracking-tighter">
                        KEPALA TOKO
                    </p>
                </div>
            </div>
        </div>

        @php
            $hasEvaluated = \App\Models\Evaluation::where('user_id', Auth::id())->exists();
            // Logic Warna: Merah (Alert) jika belum, Hijau Neon (Secure) jika sudah
            $statusColor = $hasEvaluated ? 'text-emerald-400' : 'text-red-400';
            $glowColor   = $hasEvaluated ? 'shadow-emerald-500/20' : 'shadow-red-500/20';
            $borderColor = $hasEvaluated ? 'border-emerald-500/30' : 'border-red-500/30';
            $cornerColor = $hasEvaluated ? 'border-emerald-500' : 'border-red-500';
            $bgGlow      = $hasEvaluated ? 'bg-emerald-500/10' : 'bg-red-500/10';
            $icon        = $hasEvaluated ? 'fa-check-circle' : 'fa-exclamation-triangle';
            $statusText  = $hasEvaluated ? 'SUDAH MENILAI' : 'BELUM MENILAI';
        @endphp

        {{-- CARD 2: STATUS PENILAIAN --}}
        <div class="holo-card rounded-lg p-6 border {{ $borderColor }} bg-[#0f172a]/80 backdrop-blur-sm relative group overflow-hidden hover:-translate-y-1 hover:{{ $glowColor }} transition-all duration-300">
            {{-- HUD Corners --}}
            <div class="absolute top-0 right-0 w-3 h-3 border-t-2 border-r-2 {{ $cornerColor }}"></div>
            <div class="absolute bottom-0 left-0 w-3 h-3 border-b-2 border-l-2 {{ $cornerColor }}"></div>

            <div class="absolute -left-8 -bottom-8 w-24 h-24 {{ $bgGlow }} rounded-full blur-2xl transition-all duration-500"></div>
            
             <div class="flex flex-col h-full justify-between relative z-10">
                <div class="flex items-start justify-between mb-4">
                     <div class="h-12 w-12 rounded border {{ $borderColor }} flex items-center justify-center {{ $statusColor }} bg-black/30 relative shadow-[0_0_15px_rgba(0,0,0,0.2)]">
                        <i class="fas {{ $icon }} text-xl"></i>
                        @if(!$hasEvaluated)
                            <span class="absolute -top-1 -right-1 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                            </span>
                        @endif
                    </div>
                    <span class="text-[10px] font-mono {{ $hasEvaluated ? 'text-emerald-500/50' : 'text-red-500/50' }}">STATUSES</span>
                </div>
                
                <div>
                    <p class="font-mono text-[10px] {{ $statusColor }} tracking-[0.2em] uppercase mb-1">Status Penilaian</p>
                    <p class="text-2xl font-bold font-mono text-white tracking-tighter">
                        {{ $statusText }}
                    </p>
                </div>
            </div>
        </div>

    </div>

@endsection