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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("academic_year_id");
            $table->unsignedBigInteger("class_id");
            $table->unsignedBigInteger("module_id");
            $table->unsignedBigInteger("lecturer_id");
            $table->unsignedBigInteger("venue_id");
            $table->string("day");
            $table->time("start_time");
            $table->time("end_time");
            $table->timestamps();

            $table->foreign("academic_year_id")->references("id")->on("academic_years")->onDelete("cascade");
            $table->foreign("class_id")->references("id")->on("student_classes")->onDelete("cascade");
            $table->foreign("module_id")->references("id")->on("modules")->onDelete("cascade");
            $table->foreign("lecturer_id")->references("id")->on("lecturers")->onDelete("cascade");
            $table->foreign("venue_id")->references("id")->on("venues")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('schedules');
    }
};
