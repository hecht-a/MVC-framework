<?php

class m0002_sprint2
{
    public function up()
    {
        $db = \App\Core\Application::$app->db;
        $SQL = <<<SQL
                CREATE TABLE IF NOT EXISTS `animaux` (
                  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  `type_animal` varchar(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                CREATE TABLE IF NOT EXISTS `consultations` (
                  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  `animal` int(11) NOT NULL,
                  `user` int(11) NOT NULL,
                  `type_consultation` int(11) NOT NULL,
                  `problem` longtext NOT NULL,
                  `date_prise_rdv` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                  `date_rdv` timestamp NOT NULL,
                  `done` tinyint(1) DEFAULT 0 NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                CREATE TABLE IF NOT EXISTS `type_consultation` (
                  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  `consultation` varchar(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                ALTER TABLE `consultations`
                  ADD CONSTRAINT `fk_type_animal` FOREIGN KEY (`animal`) REFERENCES `animaux` (`id`),
                  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                  ADD CONSTRAINT `fk_type_consultation` FOREIGN KEY (`type_consultation`) REFERENCES `type_consultation` (`id`);
                COMMIT;

                INSERT INTO type_consultation (consultation) VALUES ("hygiene");
                INSERT INTO type_consultation (consultation) VALUES ("bilan");
                INSERT INTO type_consultation (consultation) VALUES ("boiterie");
                INSERT INTO type_consultation (consultation) VALUES ("inconnu");
                INSERT INTO type_consultation (consultation) VALUES ("urgence");

                INSERT INTO animaux (type_animal) VALUES ("chien");
                INSERT INTO animaux (type_animal) VALUES ("chat");
                INSERT INTO animaux (type_animal) VALUES ("oiseau");
                INSERT INTO animaux (type_animal) VALUES ("rongeur");
                INSERT INTO animaux (type_animal) VALUES ("reptile");
                SQL;
        
        $db->pdo->exec($SQL);
    }
    
    public function down()
    {
        echo "Down migration" . PHP_EOL;
    }
}