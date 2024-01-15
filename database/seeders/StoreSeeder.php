<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            // Cari user dengan role 1
            $user = DB::table('users')->where('role', 1)->first();

            // Jika tidak ditemukan, buat user baru dengan role 1
            if (!$user) {
                return;
            }

            // Pastikan kita memiliki user dengan role 1 sebelum menyimpan toko
            if ($user) {
                $storeData = [
                    'name' => 'esellexpres',
                    'logo' => 'images/logo.png',
                    'slug' => 'esellexpres',
                    'user_id' => $user->id,
                ];

                DB::table('stores')->insert($storeData);
            }
    }
}
