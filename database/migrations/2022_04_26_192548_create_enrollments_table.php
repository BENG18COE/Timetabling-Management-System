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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("academic_year_id");
            $table->unsignedBigInteger("student_id");
            $table->unsignedBigInteger("class_id");
            $table->unsignedBigInteger('department_id');
            $table->string("class_mode")->default("CONTINUING");
            $table->timestamps();

            $table->foreign("department_id")->references("id")->on("departments")->onDelete("cascade");
            $table->foreign("academic_year_id")->references("id")->on("academic_years")->onDelete("cascade");
            $table->foreign("student_id")->references("id")->on("students")->onDelete("cascade");
            $table->foreign("class_id")->references("id")->on("student_classes")->onDelete("cascade");
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enrollments');
    }
};
