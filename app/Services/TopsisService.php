<?php

namespace App\Services;

use App\Models\Evaluation;
use App\Models\Criteria;
use App\Models\Candidate;
use App\Models\TopsisResult;
use Illuminate\Support\Facades\DB;

class TopsisService
{
    /**
     * Fungsi Utama: Menghitung TOPSIS untuk 1 User Tertentu
     */
    public function calculateByUser($userId)
    {
        // 1. Ambil Data Kriteria & Kandidat
        $criterias = Criteria::all();
        $candidates = Candidate::all();
        
        // Ambil semua penilaian user ini sekaligus untuk efisiensi (Eager Loading)
        $evaluations = Evaluation::where('user_id', $userId)->get();

        // Jika user belum menilai sama sekali, stop proses
        if ($evaluations->isEmpty()) {
            return false; 
        }

        // 2. Bangun Matriks Keputusan (X)
        $matrix = [];
        foreach ($candidates as $candidate) {
            foreach ($criterias as $criteria) {
                // Cari nilai yang cocok di koleksi data
                $eval = $evaluations->where('candidate_id', $candidate->id)
                                    ->where('criteria_id', $criteria->id)
                                    ->first();
                
                // Jika belum dinilai, default 1 (mencegah error), idealnya dicegah di Frontend
                $score = $eval ? $eval->score : 1; 
                $matrix[$candidate->id][$criteria->id] = $score;
            }
        }

        // 3. Normalisasi Matriks (R)
        // Rumus: r_ij = x_ij / sqrt(sum(x_ij^2))
        $normalizedMatrix = [];
        $divisors = [];

        // Hitung pembagi (akarnya jumlah kuadrat) per kriteria
        foreach ($criterias as $c) {
            $sumSquares = 0;
            foreach ($candidates as $can) {
                $val = $matrix[$can->id][$c->id];
                $sumSquares += pow($val, 2);
            }
            $divisors[$c->id] = sqrt($sumSquares);
        }

        // Bagi setiap nilai dengan pembagi
        foreach ($candidates as $can) {
            foreach ($criterias as $c) {
                $val = $matrix[$can->id][$c->id];
                $divisor = $divisors[$c->id] == 0 ? 1 : $divisors[$c->id]; // Cegah bagi 0
                $normalizedMatrix[$can->id][$c->id] = $val / $divisor;
            }
        }

        // 4. Matriks Terbobot (Y)
        // Rumus: y_ij = r_ij * weight
        $weightedMatrix = [];
        foreach ($candidates as $can) {
            foreach ($criterias as $c) {
                $weightedMatrix[$can->id][$c->id] = $normalizedMatrix[$can->id][$c->id] * $c->weight;
            }
        }

        // 5. Solusi Ideal Positif (A+) dan Negatif (A-)
        $idealPositive = [];
        $idealNegative = [];

        foreach ($criterias as $c) {
            // Ambil satu kolom nilai kriteria ini
            $columnValues = array_column($weightedMatrix, $c->id); 
            
            if ($c->type == 'benefit') {
                $idealPositive[$c->id] = max($columnValues); // Benefit: Max is Good
                $idealNegative[$c->id] = min($columnValues); // Benefit: Min is Bad
            } else {
                // Cost
                $idealPositive[$c->id] = min($columnValues); // Cost: Min is Good
                $idealNegative[$c->id] = max($columnValues); // Cost: Max is Bad
            }
        }

        // 6. Hitung Jarak (D+ dan D-) & Nilai Preferensi (V)
        $results = [];
        foreach ($candidates as $can) {
            $distPos = 0;
            $distNeg = 0;

            foreach ($criterias as $c) {
                $y = $weightedMatrix[$can->id][$c->id];
                $distPos += pow($y - $idealPositive[$c->id], 2);
                $distNeg += pow($y - $idealNegative[$c->id], 2);
            }

            $dPlus = sqrt($distPos);
            $dMinus = sqrt($distNeg);

            // Rumus V = D- / (D- + D+)
            $totalDist = $dMinus + $dPlus;
            $preference = $totalDist == 0 ? 0 : ($dMinus / $totalDist);

            $results[] = [
                'user_id' => $userId,
                'candidate_id' => $can->id,
                'preference_value' => $preference
            ];
        }

        // 7. Sorting Ranking (Nilai V tertinggi = Rank 1)
        // Kita urutkan array $results berdasarkan key 'preference_value' secara Descending
        usort($results, function($a, $b) {
            return $b['preference_value'] <=> $a['preference_value'];
        });

        // 8. Simpan ke Database (Reset data lama user ini)
        DB::beginTransaction(); // Pakai transaksi biar aman
        try {
            TopsisResult::where('user_id', $userId)->delete();

            $rank = 1;
            foreach ($results as $res) {
                TopsisResult::create([
                    'user_id' => $res['user_id'],
                    'candidate_id' => $res['candidate_id'],
                    'preference_value' => $res['preference_value'],
                    'rank' => $rank++
                ]);
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}