<?php

class CreateStudentsTable extends Migration
{
    public function up()
    {
        Schema::create("students", function ($table) {

            $table->id();

            $table->string("fullname");

            $table->string("email");

            $table->timestamps();

        });
    }

    public function down()
    {
        Schema::drop("students");
    }
}