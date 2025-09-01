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
        Schema::create('nominations', function (Blueprint $table) {
            $table->id();
            $table->string('uid');
            $table->string('ukey');
            $table->string('name');
            $table->string('designation');
            $table->string('organization');
            $table->string('category');
            $table->string('competency_name');
            $table->string('definition')->nullable();
            $table->json('competencies')->nullable();
            $table->json('priority_gaps')->nullable(); // To store the array of 3 inputs
            $table->text('final_thoughts')->nullable(); // To store the long text response
            $table->string('comment')->nullable();
            $table->string('payment')->nullable();
            $table->string('invoice')->nullable();
            $table->boolean('status')->default(true);
            $table->boolean('trash')->default(false);
            $table->boolean('pv')->default(false);
            $table->string('paymentLinkSend')->default(0);
            $table->string('confirmLinkSend')->default(0);
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
        Schema::dropIfExists('nominations');
    }
};
