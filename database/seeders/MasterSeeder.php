<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Criteria;
use App\Models\Candidate;
use Illuminate\Support\Facades\Hash;

class MasterSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Users (Password default: 123)
        $password = Hash::make('123');
        
        User::create(['name' => 'Super Admin', 'email' => 'admin@toko.com', 'password' => $password, 'role' => 'admin']);
        User::create(['name' => 'Budi (Area Mgr)', 'email' => 'area@toko.com', 'password' => $password, 'role' => 'area_manager']);
        User::create(['name' => 'Siti (Kepala Toko)', 'email' => 'store@toko.com', 'password' => $password, 'role' => 'store_manager']);
        User::create(['name' => 'Andi (HR)', 'email' => 'hr@toko.com', 'password' => $password, 'role' => 'hr']);

        // 2. Create Criterias (C1-C8)
        $criterias = [
            ['code' => 'C1', 'name' => 'Umur', 'type' => 'cost', 'weight' => 0.10],
            ['code' => 'C2', 'name' => 'Lama Bekerja', 'type' => 'benefit', 'weight' => 0.15],
            ['code' => 'C3', 'name' => 'Kinerja', 'type' => 'benefit', 'weight' => 0.20],
            ['code' => 'C4', 'name' => 'Absensi', 'type' => 'cost', 'weight' => 0.10],
            ['code' => 'C5', 'name' => 'Leadership', 'type' => 'benefit', 'weight' => 0.15],
            ['code' => 'C6', 'name' => 'Problem Solving', 'type' => 'benefit', 'weight' => 0.15],
            ['code' => 'C7', 'name' => 'Integritas', 'type' => 'benefit', 'weight' => 0.10],
            ['code' => 'C8', 'name' => 'Jarak Domisili', 'type' => 'cost', 'weight' => 0.05],
        ];
        foreach ($criterias as $c) { Criteria::create($c); }

        // 3. Create 10 Candidates Dummy
        for ($i = 1; $i <= 10; $i++) {
            Candidate::create([
                'name' => 'Kandidat ' . $i,
                'age' => rand(25, 40),
                'experience_year' => rand(2, 10),
            ]);
        }
    }
}