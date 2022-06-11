<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_wallets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userid');
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('wallet_value',8,2);
            $table->integer('request_value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_wallets');
    }
}
