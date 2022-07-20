<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_classes', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("course_id");
            $table->unsignedBigInteger("academic_year_id");
            $table->unsignedBigInteger('department_id');
            
            $table->timestamps();
            $table->foreign("department_id")->references("id")->on("departments")->onDelete("cascade");
            $table->foreign("course_id")->references("id")->on("courses")->onDelete("cascade");
            $table->foreign("academic_year_id")->references("id")->on("academic_years")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('student_classes');
    }
};
