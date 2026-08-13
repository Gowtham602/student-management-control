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
       Schema::create('parent_communications', function (Blueprint $table) {

    $table->id();

    $table->foreignId('created_by')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('department_id')
        ->nullable()
        ->constrained('departments')
        ->nullOnDelete();

    $table->foreignId('section_id')
        ->nullable()
        ->constrained('sections')
        ->nullOnDelete();

    $table->foreignId('academic_year_id')
        ->nullable()
        ->constrained('academic_years')
        ->nullOnDelete();

    $table->foreignId('template_id')
        ->nullable()
        ->constrained('parent_message_templates')
        ->nullOnDelete();

    $table->string('subject')->nullable();

    $table->text('message');

    $table->enum('status', [
        'PENDING',
        'CONFIRMED',
        'REJECTED',
        'SENDING',
        'SENT',
        'PARTIAL',
        'FAILED'
    ])->default('PENDING');

    $table->timestamp('submitted_at')->nullable();

    $table->foreignId('confirmed_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('confirmed_at')->nullable();

    $table->foreignId('rejected_by')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('rejected_at')->nullable();

    $table->text('rejection_reason')->nullable();

    $table->unsignedInteger('total_students')->default(0);
    $table->unsignedInteger('total_sent')->default(0);
    $table->unsignedInteger('total_failed')->default(0);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_communications');
    }
};
