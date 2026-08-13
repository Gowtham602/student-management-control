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
        Schema::create('parent_communication_recipients', function (Blueprint $table) {

    $table->id();

    $table->foreignId('parent_communication_id')
        ->constrained('parent_communications')
        ->cascadeOnDelete();

    $table->foreignId('student_id')
        ->constrained('students')
        ->cascadeOnDelete();

    $table->string('student_name');

    $table->string('parent_name')->nullable();

    $table->string('phone', 20);

    $table->text('message');

    $table->enum('status', [
        'PENDING',
        'SENT',
        'FAILED'
    ])->default('PENDING');

    $table->text('sms_response')->nullable();

    $table->timestamp('sent_at')->nullable();

    $table->timestamps();

    $table->index('phone');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_communication_recipients');
    }
};
