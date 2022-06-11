<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCaseAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_attachments', function (Blueprint $table) {
            $table->id();
            $table->string('file_name',999);
            $table->unsignedBigInteger('caseid');
            $table->foreign('caseid')->references('id')->on('cases')->onDelete('cascade');
            $table->unsignedBigInteger('casedetailsid');
            $table->foreign('casedetailsid')->references('id')->on('case_details')->onDelete('cascade');
            $table->unsignedBigInteger('uploadedby');
            $table->foreign('uploadedby')->references('id')->on('admins')->onDelete('cascade');
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
        Schema::dropIfExists('cases_attachments');
    }
}
