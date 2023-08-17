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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('Index');
            $table->string('User Id');
            $table->string('First Name');
            $table->string('Last Name');
            $table->string('Sex');
            $table->string('Email');
            $table->string('Phone');
            $table->string('Date of birth');
            $table->string('Job Title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
