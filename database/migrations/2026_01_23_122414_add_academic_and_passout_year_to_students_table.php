<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // 1, 2, 3, 4 (current studying year)
            $table->unsignedTinyInteger('academic_year')
                  ->nullable()
                  ->after('section');
        });
    }

    
        public function down(): void
{
    Schema::table('students', function (Blueprint $table) {
        if (Schema::hasColumn('students', 'academic_year')) {
            $table->dropColumn('academic_year');
        }
    });
}

 
};
