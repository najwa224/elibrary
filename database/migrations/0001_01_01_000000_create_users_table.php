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
        $table->string('username')->unique(); // اسم المستخدم
        $table->string('password');           // كلمة السر
        $table->string('fname');              // الاسم الأول
        $table->string('lname');              // الاسم الأخير
        $table->timestamps();                 // created_at و updated_at
    });

    // يمكن حذف الجداول التالية إن لم تكن تستخدمها
    Schema::dropIfExists('password_reset_tokens');
    Schema::dropIfExists('sessions');
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
