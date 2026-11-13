<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peran', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique(); // karyawan, atasan, admin_hr
            $table->string('nama', 50);
            $table->boolean('aktif')->default(true);
        });

        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peran_id')->constrained('peran');
            $table->string('nama', 100);
            $table->string('email', 100)->unique();
            $table->string('password');
            $table->boolean('aktif')->default(true);
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->timestamp('diperbarui_pada')->useCurrent()->useCurrentOnUpdate();
        });

        Schema::create('bagian_organisasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->string('nama', 100);
            $table->boolean('aktif')->default(true);
        });

        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->unique()->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('bagian_organisasi_id')->constrained('bagian_organisasi');
            $table->foreignId('atasan_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->string('nomor_induk', 30)->unique();
            $table->string('nama', 100);
            $table->date('tanggal_masuk');
            $table->unsignedInteger('jatah_cuti')->default(12);
            $table->boolean('aktif')->default(true);
        });

        Schema::create('saldo_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->year('tahun');
            $table->unsignedInteger('jatah_awal')->default(12);
            $table->unsignedInteger('terpakai')->default(0);
            $table->unsignedInteger('saldo_tersedia')->default(12);
            $table->string('catatan')->nullable();
            $table->unique(['pegawai_id', 'tahun']);
        });

        Schema::create('hari_libur', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->unique();
            $table->string('nama', 100);
            $table->enum('jenis', ['nasional', 'perusahaan']);
            $table->boolean('aktif')->default(true);
        });

        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_pengajuan', 30)->unique();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();
            $table->foreignId('atasan_penyetuju_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->enum('status', ['draf', 'menunggu_atasan', 'menunggu_hr', 'disetujui', 'ditolak', 'dibatalkan']);
            $table->text('alasan');
            $table->date('tanggal_cuti');
            $table->timestamp('diajukan_pada')->useCurrent();
            $table->timestamp('dibatalkan_pada')->nullable();
        });

        Schema::create('persetujuan_cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_cuti_id')->constrained('pengajuan_cuti')->cascadeOnDelete();
            $table->foreignId('pemberi_keputusan_id')->constrained('pegawai');
            $table->enum('tahap', ['atasan', 'admin_hr']);
            $table->enum('keputusan', ['disetujui', 'ditolak']);
            $table->text('catatan')->nullable();
            $table->timestamp('diputuskan_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('persetujuan_cuti');
        Schema::dropIfExists('pengajuan_cuti');
        Schema::dropIfExists('hari_libur');
        Schema::dropIfExists('saldo_cuti');
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('bagian_organisasi');
        Schema::dropIfExists('pengguna');
        Schema::dropIfExists('peran');
    }
};