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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('lecturer_id');
            $table->string("name");
            $table->string("code");
            $table->string("type")->default("core");
            $table->string("credits");
            $table->string("capacity");
            $table->longText("description")->nullable();
            $table->timestamps();

            $table->foreign("lecturer_id")->references("id")->on("lecturers")->onDelete("cascade");
            $table->foreign("department_id")->references("id")->on("departments")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
};
