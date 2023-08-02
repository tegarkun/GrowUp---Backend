<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fundings', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->longText("campaign");
            $table->string("fund_raise_use");
            $table->string("image");
            $table->string("benefit");
            $table->integer("target_amount");
            $table->integer("current_amount");
            $table->integer("day_left");
            $table->boolean("status");
            $table->unsignedBigInteger('ukm_id');
            $table->foreign('ukm_id')->references('id')->on('ukms')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fundings');
    }
};
