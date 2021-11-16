<?php

use App\Core\Application;

class m0001_sprint1
{
    public function up()
    {
        $db = Application::$app->db;
        $SQL = <<<SQL
                CREATE TABLE IF NOT EXISTS `users` (
                  `id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  `email` varchar(50) NOT NULL,
                  `password` varchar(255) NOT NULL,
                  `firstname` varchar(50) NOT NULL,
                  `lastname` varchar(50) NOT NULL,
                  `tel` varchar(10) DEFAULT NULL,
                  `admin` tinyint(1) DEFAULT 0,
                  `post_code` int(5) NOT NULL,
                  `city` varchar(255) NOT NULL,
                  `address` varchar(255) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
                SQL;
        
        $db->pdo->exec($SQL);
    }
    
    public function down()
    {
        echo "Down migration" . PHP_EOL;
    }
}