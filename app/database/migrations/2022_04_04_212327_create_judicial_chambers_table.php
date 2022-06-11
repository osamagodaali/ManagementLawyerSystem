<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJudicialChambersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('judicial_chambers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('courtid');
            $table->foreign('courtid')->references('id')->on('courts')->onDelete('cascade');
            $table->string('address');
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
        Schema::dropIfExists('judicial_chambers');
    }
}
