<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->id();
            $table->string('case_number');
            $table->string('title');
            $table->unsignedBigInteger('userid');
            $table->foreign('userid')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('brancheid');
            $table->foreign('brancheid')->references('id')->on('branches')->onDelete('cascade');
            $table->unsignedBigInteger('followby');
            $table->foreign('followby')->references('id')->on('admins')->onDelete('cascade');
            $table->text('details');
            $table->decimal('value',8,2);
            $table->boolean('case_status')->default(0);
            $table->boolean('payment_status')->default(0);
            $table->timestamp('start_case')->nullable();
            $table->timestamp('end_case')->nullable();
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
        Schema::dropIfExists('cases');
    }
}
