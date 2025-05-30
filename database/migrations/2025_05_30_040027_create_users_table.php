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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string("email",100)->nullable(false)->unique("unique_email");;
            $table->string("password",100)->nullable(false);
            $table->string("name",50)->nullable(false);;
            $table->string("company",30)->nullable(true);;
            $table->string("token",100)->nullable(true)->unique("unique_token");;;;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
