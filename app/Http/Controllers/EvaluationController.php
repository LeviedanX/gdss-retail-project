<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Candidate;
use App\Models\Criteria;
use App\Models\Evaluation;
use App\Services\TopsisService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EvaluationController extends Controller
{
    protected $topsisService;

    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    // 1. Tampilkan Form Input
    public function index()
    {
        // Cek apakah user sudah pernah menilai?
        $hasEvaluated = Evaluation::where('user_id', Auth::id())->exists();

        // Ambil data master
        $candidates = Candidate::all();
        $criterias = Criteria::all();

        return view('evaluation.input', compact('candidates', 'criterias', 'hasEvaluated'));
    }

    // 2. Simpan Nilai ke Database
    public function store(Request $request)
    {
        // Validasi dasar
        $request->validate([
            'scores' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            $userId = Auth::id();

            // Hapus penilaian lama jika ada (Reset)
            Evaluation::where('user_id', $userId)->delete();

            // Loop input dari form
            // Struktur name di HTML nanti: scores[candidate_id][criteria_id]
            foreach ($request->scores as $candidateId => $criteriaScores) {
                foreach ($criteriaScores as $criteriaId => $score) {
                    Evaluation::create([
                        'user_id' => $userId,
                        'candidate_id' => $candidateId,
                        'criteria_id' => $criteriaId,
                        'score' => $score
                    ]);
                }
            }

            // Otomatis Hitung TOPSIS Individu setelah save
            $this->topsisService->calculateByUser($userId);

            DB::commit();
            return redirect()->route('evaluation.index')->with('success', 'Penilaian berhasil disimpan & dihitung!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}