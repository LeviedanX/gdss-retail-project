<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Candidate;
use App\Models\Criteria;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // 1. Dashboard Utama Admin
    public function index()
    {
        $users = User::all();
        $candidates = Candidate::all();
        $criterias = Criteria::all();

        return view('dashboard.admin', compact('users', 'candidates', 'criterias'));
    }

    // --- MANAJEMEN USER (CHANGE PASSWORD) ---
    public function changePassword(Request $request, $id)
    {
        // Validasi input password baru
        $request->validate([
            'password' => 'required|string|min:8', // Minimal 8 karakter (bisa disesuaikan)
        ]);

        $user = User::findOrFail($id);
        // Mengubah password sesuai inputan admin
        $user->password = Hash::make($request->password); 
        $user->save();

        return back()->with('success', 'Password user ' . $user->name . ' berhasil diubah.');
    }
    
    // --- MANAJEMEN KANDIDAT ---
    public function storeCandidate(Request $request)
    {
        // Validasi semua field wajib
        $request->validate([
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:18',
            'experience_year' => 'required|integer|min:0'
        ]);

        Candidate::create([
        'name' => strtoupper($request->name),
        'age' => $request->age,
        'experience_year' => $request->experience_year,
        ]);
        
        return back()->with('success', 'Kandidat berhasil ditambahkan.');
    }

    public function updateCandidate(Request $request, $id)
    {
        $candidate = Candidate::findOrFail($id);
        $candidate->update($request->all());
        return back()->with('success', 'Data kandidat berhasil diupdate.');
    }

    public function deleteCandidate($id)
    {
        // Cek apakah kandidat sudah pernah dinilai
        if (\App\Models\Evaluation::where('candidate_id', $id)->exists()) {
            return back()->with('error', 'Gagal hapus! Kandidat ini sudah memiliki data penilaian. Hapus penilaian dulu.');
        }
        
        Candidate::destroy($id);
        return back()->with('success', 'Kandidat berhasil dihapus.');
    }

    // --- MANAJEMEN KRITERIA ---
    public function storeCriteria(Request $request)
    {
        // Validasi sederhana
        $request->validate(['code' => 'required', 'name' => 'required', 'weight' => 'required']);
        $criteria = Criteria::create($request->all());
        return back()->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function updateCriteria(Request $request, $id)
    {
        $criteria = Criteria::findOrFail($id);
        $criteria->update($request->all());
        return back()->with('success', 'Kriteria berhasil diupdate.');
    }
    
    public function deleteCriteria($id)
    {
        // Cek apakah kriteria sudah digunakan
        if (\App\Models\Evaluation::where('criteria_id', $id)->exists()) {
            return back()->with('error', 'Gagal hapus! Kriteria ini sedang digunakan dalam penilaian.');
        }

        Criteria::destroy($id);
        return back()->with('success', 'Kriteria berhasil dihapus.');
    }
}