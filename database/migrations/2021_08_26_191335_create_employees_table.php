<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string("fullname");
            $table->string("position");
            $table->date("dateOfEmployment");
            $table->string("phone");
            $table->string("email");
            $table->decimal("salary");
            $table->string("photo")->nullable();
            $table->timestamps();
            $table->bigInteger("admin_created_id")->unsigned();
            $table->bigInteger("admin_updated_id")->unsigned();
            $table->bigInteger("boss_id")->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
