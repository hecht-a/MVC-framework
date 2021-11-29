<?php

namespace App\Core;

abstract class BaseMigration
{
    /**
     * Créé la migration dans la db
     * @return mixed
     */
    abstract public function up();
    
    abstract public function down();
}