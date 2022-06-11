<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_details', function (Blueprint $table) {
            $table->id();
            $table->text('details');
            $table->unsignedBigInteger('caseid');
            $table->foreign('caseid')->references('id')->on('cases')->onDelete('cascade');
            $table->unsignedBigInteger('followby');
            $table->foreign('followby')->references('id')->on('admins')->onDelete('cascade');
            $table->text('nextfollowdate');
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
        Schema::dropIfExists('case_details');
    }
}
