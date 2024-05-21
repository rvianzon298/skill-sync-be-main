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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('sex');
            $table->string('birthdate');
            $table->string('address');
            $table->string('street')->nullable();
            $table->string('barangay')->nullable();
            $table->string('province')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('contact_number');
            $table->string('experience')->nullable();
            $table->string('educational_attainment')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->boolean('is_employer')->default(false);
            $table->boolean('is_jobseeker')->default(false);
            $table->boolean('is_admin')->default(false);
            $table->string('profile_picture')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('category_id')->nullable()->constrained('categories');
            $table->string('resume')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
