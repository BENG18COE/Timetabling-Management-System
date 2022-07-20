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
        Schema::create('enroll_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("enroll_id");
            $table->unsignedBigInteger("module_id");
            $table->timestamps();

            $table->foreign("enroll_id")->references("id")->on("enrollments")->onDelete("cascade");
            $table->foreign("module_id")->references("id")->on("modules")->onDelete("cascade");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('enroll_modules');
    }
};
