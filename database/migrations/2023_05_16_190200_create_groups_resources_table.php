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
        Schema::create('groups_resources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('value');
            $table->unsignedBigInteger('payed_by');
            $table->unsignedBigInteger('must_pay')->nullable();
            $table->timestamps();
            $table->foreign('payed_by')->references('id')->on('users');
            $table->foreign('must_pay')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups_resources');
    }
};
