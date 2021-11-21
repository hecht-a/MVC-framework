<?php

use App\Core\Application;
use App\Core\BaseMigration;

class m0003_sprint2_bis extends BaseMigration
{
    public function up()
    {
        $db = Application::$app->db;
        $SQL = <<<SQL
                DELETE FROM type_consultation;

                INSERT INTO type_consultation (consultation) VALUES ("Vaccination");
                INSERT INTO type_consultation (consultation) VALUES ("Bilan de santé");
                INSERT INTO type_consultation (consultation) VALUES ("Problèmes de boiterie");
                INSERT INTO type_consultation (consultation) VALUES ("Problèmes inconnu");
                INSERT INTO type_consultation (consultation) VALUES ("Problème digestif");
                INSERT INTO type_consultation (consultation) VALUES ("Traumatismes divers");
                INSERT INTO type_consultation (consultation) VALUES ("Dermatologie");
                INSERT INTO type_consultation (consultation) VALUES ("Sterilisation / Castration");
                INSERT INTO type_consultation (consultation) VALUES ("Fin de vie");
                INSERT INTO type_consultation (consultation) VALUES ("Consultation urgente");
                INSERT INTO type_consultation (consultation) VALUES ("Autre");
                SQL;
        
        $db->pdo->exec($SQL);
    }
    
    public function down()
    {
        echo "Down migration" . PHP_EOL;
    }
}