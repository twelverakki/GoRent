<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Tool;
use App\Models\Rental;
use App\Models\RentalItem;
use App\Models\Fine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT USER (Admin & Customer)
        // ------------------------------------------
        $admin = User::create([
            'name' => 'Kennan Admin',
            'email' => 'admin@gorent.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Markas Besar GoRent',
        ]);

        $customer1 = User::create([
            'name' => 'Jems Customer', // User Baik
            'email' => 'jems@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '08987654321',
            'address' => 'Jl. Perintis Kemerdekaan, Makassar',
        ]);

        $customerBad = User::create([
            'name' => 'Bad Boy', // User yang diberi skenario denda
            'email' => 'bad@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'phone' => '08111111111',
            'address' => 'Jl. Denda No. 1',
        ]);

        // 2. BUAT KATEGORI
        // ------------------------------------------
        $catCamera = Category::create(['name' => 'Kamera', 'slug' => 'kamera']);
        $catLens = Category::create(['name' => 'Lensa', 'slug' => 'lensa']);
        $catLighting = Category::create(['name' => 'Lighting', 'slug' => 'lighting']);
        $catAudio = Category::create(['name' => 'Audio', 'slug' => 'audio']);

        // 3. BUAT ALAT (TOOLS)
        // ------------------------------------------
        $tools = [];

        // Kamera
        $tools[] = Tool::create([
            'category_id' => $catCamera->id,
            'name' => 'Sony A7 III Body Only',
            'slug' => 'sony-a7-iii',
            'description' => 'Kamera Mirrorless Fullframe terbaik untuk lowlight.',
            'price_per_day' => 250000,
            'stock' => 5,
            'is_available' => true,
        ]);

        $tools[] = Tool::create([
            'category_id' => $catCamera->id,
            'name' => 'Fujifilm X-T4',
            'slug' => 'fujifilm-xt4',
            'description' => 'Kamera APS-C dengan warna film simulation yang ikonik.',
            'price_per_day' => 200000,
            'stock' => 3,
            'is_available' => true,
        ]);

        // Lensa
        $tools[] = Tool::create([
            'category_id' => $catLens->id,
            'name' => 'Sony FE 24-70mm f/2.8 GM',
            'slug' => 'sony-24-70-gm',
            'description' => 'Lensa sapu jagat kualitas G Master.',
            'price_per_day' => 150000,
            'stock' => 4,
            'is_available' => true,
        ]);

        // Lighting
        $tools[] = Tool::create([
            'category_id' => $catLighting->id,
            'name' => 'Godox SL60W',
            'slug' => 'godox-sl60w',
            'description' => 'Lampu studio LED continuous light.',
            'price_per_day' => 75000,
            'stock' => 10,
            'is_available' => true,
        ]);

        // 4. BUAT TRANSAKSI (History Sewa)
        // ------------------------------------------

        // Skenario A: Transaksi Selesai (Completed) milik Jems
        $rental1 = Rental::create([
            'user_id' => $customer1->id,
            'invoice_no' => 'INV-TEST-001',
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->subDays(2),
            'return_date' => Carbon::now()->subDays(2),
            'total_price' => 750000, // 250rb x 3 hari
            'status' => 'completed',
        ]);

        RentalItem::create([
            'rental_id' => $rental1->id,
            'tool_id' => $tools[0]->id, // Sony A7 III
            'quantity' => 1,
            'price_snapshot' => 250000,
            'subtotal' => 750000,
        ]);


        // 5. BUAT SKENARIO DENDA (Middleware Test)
        // ------------------------------------------

        // Skenario B: Transaksi dengan Denda Belum Lunas milik 'Bad Boy'
        $rentalBad = Rental::create([
            'user_id' => $customerBad->id,
            'invoice_no' => 'INV-BAD-001',
            'start_date' => Carbon::now()->subMonth(1),
            'end_date' => Carbon::now()->subMonth(1)->addDays(2),
            'return_date' => Carbon::now()->subMonth(1)->addDays(4), // Telat 2 hari
            'total_price' => 500000,
            'status' => 'completed', // Barang sudah kembali, tapi...
        ]);

        // Buat Dendanya
        Fine::create([
            'rental_id' => $rentalBad->id,
            'amount' => 100000, // Denda 100rb
            'reason' => 'Terlambat pengembalian 2 hari.',
            'status' => 'unpaid', // STATUS INI YANG AKAN MEMICU MIDDLEWARE
        ]);
    }
}