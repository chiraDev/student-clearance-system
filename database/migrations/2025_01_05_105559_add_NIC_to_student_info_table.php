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
    Schema::table('student_info', function (Blueprint $table) {
        $table->string('NIC')->nullable(); // Adjust the type and constraints as needed
    });
}

public function down()
{
    Schema::table('student_info', function (Blueprint $table) {
        $table->dropColumn('NIC');
    });
}
};
