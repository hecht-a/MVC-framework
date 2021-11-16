<?php

use App\Core\Application;

class m0002_sprint2
{
    public function up()
    {
        $db = Application::$app->db;
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
                  `rdv` int(11) NOT NULL,
                  `domicile` tinyint(1) DEFAULT 0 NOT NULL,
                  `done` tinyint(1) DEFAULT 0 NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                CREATE TABLE IF NOT EXISTS `type_consultation` (
                  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  `consultation` varchar(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                CREATE TABLE IF NOT EXISTS `times` (
                  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  `day` timestamp NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                ALTER TABLE `consultations`
                  ADD CONSTRAINT `fk_type_animal` FOREIGN KEY (`animal`) REFERENCES `animaux` (`id`),
                  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
                  ADD CONSTRAINT `fk_type_consultation` FOREIGN KEY (`type_consultation`) REFERENCES `type_consultation` (`id`),
                  ADD CONSTRAINT `fk_rdv` FOREIGN KEY (`rdv`) REFERENCES `times` (`id`);

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
        
        $hours = ["08:00", "09:00", "10:00", "11:00", "14:00", "15:00", "16:00", "17:00"];
        
        $now = (new DateTime());
        for ($i = 0; $i < 7; $i++) {
            $isWeekend = fn(int $day): bool => $day > 5;
            if ($isWeekend(intval($now->format("N")))) {
                do {
                    $now = $now->modify("+1 day");
                } while ($isWeekend(intval($now->format("N"))));
            }
            foreach ($hours as $hour) {
                [$h, $m] = array_map(fn($a) => intval($a), preg_split("/:/", $hour));
                $now = $now->setTime($h, $m);
                $date = $now->format("Y-m-d H:i");
                $SQL .= "\nINSERT INTO times (day) VALUES (\"$date\");";
            }
            $now = $now->modify("+1 day");
        }
        $db->pdo->exec($SQL);
    }
    
    public function down()
    {
        echo "Down migration" . PHP_EOL;
    }
}