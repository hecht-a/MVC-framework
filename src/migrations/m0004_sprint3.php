<?php

use App\Core\Application;
use App\Core\BaseMigration;

class m0004_sprint3 extends BaseMigration
{
    public function up()
    {
        $db = Application::$app->db;
        $SQL = <<<SQL
                CREATE TABLE IF NOT EXISTS animals (
                  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  `name` varchar(255) NOT NULL,
                  `height` int(11) DEFAULT 0,
                  `weight` int(11) DEFAULT 0,
                  `type` varchar(255),
                  `age` int(11) DEFAULT 0,
                  `owner` int(11) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                ALTER TABLE consultations
                    DROP CONSTRAINT `fk_type_animal`;

                ALTER TABLE consultations
                    ADD CONSTRAINT `fk_type_animal` FOREIGN KEY (`animal`) REFERENCES `animals` (`id`);

                ALTER TABLE animals
                  ADD CONSTRAINT `fk_owner` FOREIGN KEY (`owner`) REFERENCES `users` (`id`);

                DROP TABLE IF EXISTS animaux;
               SQL;
        $db->pdo->exec($SQL);
    }
    
    public function down()
    {
        // TODO: Implement down() method.
    }
}