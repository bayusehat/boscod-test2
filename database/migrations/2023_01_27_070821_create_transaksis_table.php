<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaksi_transfers', function (Blueprint $table) {
            $table->string('id_transaksi')->unique();
            $table->integer('kode_unik')->unique()->index();
            $table->integer('bank_pengirim');
            $table->integer('bank_tujuan');
            $table->string('rekening_tujuan');
            $table->string('atasnama_tujuan');
            $table->bigInteger('nilai_transfer');
            $table->bigInteger('total_transfer');
            $table->integer('id_user');
            $table->integer('status_transfer')->default(0);
            $table->datetime('expired_transfer');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaksis');
    }
};
