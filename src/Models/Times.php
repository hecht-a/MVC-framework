<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\Application;
use App\Core\DbModel;
use DateTime;
use Exception;
use PDO;

class Times extends DbModel
{
    private static array $hours = ["08:00", "09:00", "10:00", "11:00", "14:00", "15:00", "16:00", "17:00"];
    public string $id;
    public string $day;
    
    public static function primaryKey(): string
    {
        return "id";
    }
    
    public static function lastDay(): Times
    {
        $array = self::findAllFree();
        return end($array);
    }
    
    public static function findAllFree(): array
    {
        return array_filter(self::findNextDays(), fn($time) => !Consultation::findOne(["rdv" => $time->id]));
    }
    
    public static function findNextDays(): bool|array
    {
        $now = (new DateTime())->format("Y-m-d H:i");
        $tableName = static::tableName();
        $statement = self::prepare("SELECT * FROM  $tableName WHERE day > '$now'");
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_CLASS, static::class);
    }
    
    public static function tableName(): string
    {
        return "times";
    }
    
    /**
     * @throws Exception
     */
    public static function addDays()
    {
        $displayedDays = self::amountDisplayedDays();
        $SQL = "";
        if ($displayedDays < 5) {
            $now = (new DateTime());
            for ($i = 0; $i < 7 - $displayedDays; $i++) {
                $isWeekend = fn(int $day): bool => $day > 5;
                if ($isWeekend(intval($now->format("N")))) {
                    do {
                        $now = $now->modify("+1 day");
                    } while ($isWeekend(intval($now->format("N"))));
                }
                $hours = self::getNeededDaysForADay($now->format("Y-m-d"));
                foreach ($hours as $hour) {
                    [$h, $m] = array_map(fn($a) => intval($a), preg_split("/:/", $hour));
                    $now = $now->setTime($h, $m);
                    $date = $now->format("Y-m-d H:i");
                    $SQL .= "\nINSERT INTO times (day) VALUES (\"$date\");";
                }
                $now = $now->modify("+1 day");
            }
        }
        if ($SQL !== "") {
            Application::$app->db->pdo->exec($SQL);
        }
    }
    
    public static function amountDisplayedDays(): int
    {
        return intval(floor(count(self::findAllFree()) / 8));
    }
    
    /**
     * @throws Exception
     */
    public static function getNeededDaysForADay(string $day): array
    {
        $tableName = self::tableName();
        $statement = self::prepare("SELECT * from $tableName WHERE day LIKE \"%$day%\"");
        $statement->execute();
        /** @var Times[] $data */
        $data = $statement->fetchAll(PDO::FETCH_CLASS, self::class);
        $hours = array_map(function($time) {
            [$h, $m] = preg_split("/:/", preg_split("/ /", $time)[1]);
            return "$h:$m";
        }, array_map(fn($time) => $time->day, $data));
        
        if (!empty($hours)) {
            return [];
        }
        
        return array_diff(self::$hours, $hours);
    }
    
    public function attributes(): array
    {
        return ["day"];
    }
    
    public function rules(): array
    {
        return [];
    }
    
    public function data(): array
    {
        return [
            "id" => $this->id ?? "",
            "day" => $this->day ?? ""
        ];
    }
    
    /**
     * @throws Exception
     */
    public function __toString(): string
    {
        return strftime("%A %e %B à %H:%M", (new \DateTime($this->day))->getTimestamp());
    }
}