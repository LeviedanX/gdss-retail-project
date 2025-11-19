<?php

namespace App\Services;

use App\Models\TopsisResult;
use App\Models\BordaResult;
use App\Models\ConsensusLog;
use App\Models\User;
use App\Models\Candidate;
use Illuminate\Support\Facades\DB;

class BordaService
{
    public function calculateConsensus($userId)
    {
        // 1. Validasi: Hanya Area Manager (Role Tertinggi) yang boleh klik
        $user = User::find($userId);
        if ($user->role !== 'area_manager') {
            throw new \Exception("Akses Ditolak. Hanya Area Manager yang bisa memicu konsensus.");
        }

        // 2. Ambil Hasil TOPSIS dari Semua User
        // Kita butuh memastikan minimal ada data dari para DM
        $topsisResults = TopsisResult::all();

        if ($topsisResults->isEmpty()) {
            throw new \Exception("Belum ada data penilaian dari Decision Maker.");
        }

        // 3. Algoritma Borda Count
        // Rumus: Poin = (Total Kandidat - Ranking + 1)
        // Contoh: Ada 10 Kandidat. Juara 1 dapat 10 poin. Juara 10 dapat 1 poin.
        
        $totalCandidates = Candidate::count();
        $bordaScores = [];

        foreach ($topsisResults as $result) {
            $candidateId = $result->candidate_id;
            $rank = $result->rank;

            // Hitung poin berdasarkan ranking
            $points = $totalCandidates - $rank + 1;

            // Akumulasi poin ke kandidat tersebut
            if (!isset($bordaScores[$candidateId])) {
                $bordaScores[$candidateId] = 0;
            }
            $bordaScores[$candidateId] += $points;
        }

        // 4. Simpan Hasil ke Database
        DB::beginTransaction();
        try {
            // Catat Log: Siapa yang melakukan konsensus & Kapan
            $log = ConsensusLog::create([
                'triggered_by' => $userId
            ]);

            // Simpan Detail Nilai Borda & Ranking Final
            // Sort array scores dari tertinggi ke terendah (Descending)
            arsort($bordaScores);

            $finalRank = 1;
            foreach ($bordaScores as $candId => $totalPoints) {
                BordaResult::create([
                    'consensus_log_id' => $log->id,
                    'candidate_id' => $candId,
                    'total_points' => $totalPoints,
                    'final_rank' => $finalRank++
                ]);
            }

            DB::commit();
            return $log->id; // Kembalikan ID Log untuk ditampilkan
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}