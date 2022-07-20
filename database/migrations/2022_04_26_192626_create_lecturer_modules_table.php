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
        Schema::create('lecturer_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("academic_year_id");
            $table->unsignedBigInteger("lecturer_id");
            $table->unsignedBigInteger("module_id");
            $table->unsignedBigInteger('department_id');
            $table->timestamps();

            $table->foreign("department_id")->references("id")->on("departments")->onDelete("cascade");
            $table->foreign("academic_year_id")->references("id")->on("academic_years")->onDelete("cascade");
            $table->foreign("module_id")->references("id")->on("modules")->onDelete("cascade");
            $table->foreign("lecturer_id")->references("id")->on("lecturers")->onDelete("cascade");
            

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lecturer_modules');
    }
};
