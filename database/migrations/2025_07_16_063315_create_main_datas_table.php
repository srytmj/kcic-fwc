<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('maindata', function (Blueprint $table) {
            $table->id();
            $table->string('id_fwc')->unique();
            $table->string('nama');
            $table->string('id_num');
            $table->string('email')->nullable();
            $table->string('no_hp')->nullable();
            $table->date('tgl_reg');
            $table->date('tgl_exp');
            $table->string('relasi_fwc'); // 01, 02, 03
            $table->string('jenis_fwc');  // G, S
            $table->integer('kuota');
            $table->timestamps();
        });
        Schema::create('subdata', function (Blueprint $table) {
            $table->id();
            $table->string('id_pesanan')->unique();
            $table->string('id_fwc'); // foreign key to maindata.id_fwc
            // $table->foreignId('main_data_id')->constrained('main_data')->onDelete('cascade');
            $table->date('tgl_departure');
            $table->string('user');
            $table->timestamps(); // created_at = tanggal redeem
        });
        // insert data
        DB::table('maindata')->insert([
            'id_fwc' => 'G010720001',
            'nama' => 'Contoh Nama',
            'id_num' => '1234567890',
            'email' => '-',
            'no_hp' => '-',
            'tgl_reg' => now(),
            'tgl_exp' => now()->addDays(60),
            'relasi_fwc' => '01',
            'jenis_fwc' => 'G',
            'kuota' => 10,
        ]);

        DB::table('subdata')->insert([
            'id_pesanan' => 'PSN001',
            'id_fwc' => 'G010720001',
            'tgl_departure' => now()->addDays(1),
            'user' => 'Contoh User',
            'created_at' => now(),
            'updated_at' => now(), // This is the redeem date
        ]);
        DB::table('subdata')->insert([
            'id_pesanan' => 'PSN002',
            'id_fwc' => 'G010720001',
            'tgl_departure' => now()->addDays(2),
            'user' => 'Contoh User 2',
            'created_at' => now(),
            'updated_at' => now(), // This is the redeem date
        ]);
        DB::table('subdata')->insert([
            'id_pesanan' => 'PSN003',
            'id_fwc' => 'G010720001',
            'tgl_departure' => now()->addDays(3),
            'user' => 'Contoh User 3',
            'created_at' => now(),
            'updated_at' => now(), // This is the redeem date
        ]);
        DB::table('subdata')->insert([
            'id_pesanan' => 'PSN004',
            'id_fwc' => 'G010720001',
            'tgl_departure' => now()->addDays(4),
            'user' => 'Contoh User 4',
            'created_at' => now(),
            'updated_at' => now(), // This is the redeem date
        ]);
        DB::table('subdata')->insert([
            'id_pesanan' => 'PSN005',
            'id_fwc' => 'G010720001',
            'tgl_departure' => now()->addDays(5),
            'user' => 'Contoh User 5',
            'created_at' => now(),
            'updated_at' => now(), // This is the redeem date
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('main_datas');
    }
};
