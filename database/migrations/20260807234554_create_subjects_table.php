<?php

class CreateSubjectsTable extends Migration
{
    /**
     * Run the migration.
     */
    public function up()
    {
    Schema::create("subjects", function ($table) {
        $table->id();
        $table->string("name");
        $table->timestamps();
    });
    }

    /**
     * Reverse the migration.
     */
    public function down()
    {
        Schema::drop("subjects");

    }
}