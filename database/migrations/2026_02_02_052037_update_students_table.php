<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {

            // remove old columns if exists
            $table->dropColumn(['department', 'section']);

            // add relations
            $table->foreignId('department_id')
                ->after('father_phone')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('section_id')
                ->after('department_id')
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropForeign(['section_id']);
            $table->dropColumn(['department_id', 'section_id']);

            $table->string('department')->nullable();
            $table->string('section')->nullable();
        });
    }
};
