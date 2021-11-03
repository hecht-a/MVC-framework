<?php
declare(strict_types=1);

namespace App\Core;


class Database
{
    public \PDO $pdo;
    
    public function __construct(array $config)
    {
        $dsn = $config["dsn"] ?? "";
        $user = $config["user"] ?? "";
        $password = $config["password"] ?? "";
        $this->pdo = new \PDO($dsn, $user, $password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }
    
    public function applyMigrations()
    {
        $this->createMigrationTable();
        $appliedMigrations = $this->getAppliedMigrations();
        
        $newMigrations = [];
        $files = scandir(Application::$ROOT_DIR . "/migrations");
        $toApplyMigration = array_diff($files, $appliedMigrations);
        foreach ($toApplyMigration as $migration) {
            if ($migration === "." || $migration === "..") {
                continue;
            }
            require_once Application::$ROOT_DIR . "/migrations/" . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $this->log("Applying migration $migration");
            $instance->up();
            $this->log("Applied migration $migration");
            $newMigrations[] = $migration;
        }
        
        if (!empty($newMigrations)) {
            $newMigrations = array_map(fn($m) => "('$m')", $newMigrations);
            foreach ($newMigrations as $migration) {
                $this->saveMigrations($migration);
            }
        } else {
            $this->log("All migrations are applied.");
        }
    }
    
    public function createMigrationTable()
    {
        $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )  ENGINE=INNODB;");
    }
    
    public function getAppliedMigrations(): array
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function saveMigrations($migration)
    {
        $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES $migration");
        $statement->execute();
    }
    
    public function prepare($sql) {
        return $this->pdo->prepare($sql);
    }
    
    protected function log($message)
    {
        echo "[" . date("d-m-Y H:i:s") . "] - " . $message . PHP_EOL;
    }
}