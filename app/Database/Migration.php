<?php

abstract class Migration
{
    /**
     * Run the migration.
     */
    abstract public function up();

    /**
     * Reverse the migration.
     */
    abstract public function down();
}