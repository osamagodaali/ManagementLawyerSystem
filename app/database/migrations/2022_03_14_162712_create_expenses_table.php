<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->decimal('value',8,0);
            $table->text('details');
            $table->unsignedBigInteger('userid');
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('caseid');
            $table->foreign('caseid')->references('id')->on('cases')->onDelete('cascade');
            $table->unsignedBigInteger('addedby');
            $table->foreign('addedby')->references('id')->on('admins')->onDelete('cascade');
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('expenses');
    }
}
