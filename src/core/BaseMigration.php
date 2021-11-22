<?php

namespace App\Core;

abstract class BaseMigration
{
    abstract public function up();
    
    abstract public function down();
}