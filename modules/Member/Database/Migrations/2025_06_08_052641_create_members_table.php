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
        Schema::create('members', function (Blueprint $table) {
            $table->id(); // MemberID
            $table->string('name');
            $table->enum('member_type', ['Student', 'Teacher', 'Staff']);
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('profile_image')->nullable(); // <-- ADD THIS LINE
            $table->date('registration_date')->default(now());
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
