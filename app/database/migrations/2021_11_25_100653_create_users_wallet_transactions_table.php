<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersWalletTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('userid');
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->decimal('value',8,2);
            $table->text('details');
            $table->integer('type');
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
        Schema::dropIfExists('users_wallet_transactions');
    }
}
