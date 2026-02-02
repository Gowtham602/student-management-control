<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::table('students', function (Blueprint $table) {
        $table->year('admission_year')->nullable()->after('section_id');
        $table->dropColumn('year');
    });
}

public function down()
{
    Schema::table('students', function (Blueprint $table) {
        $table->year('year')->nullable();
        $table->dropColumn('admission_year');
    });
}

};
