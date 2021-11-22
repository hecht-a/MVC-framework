<?php
declare(strict_types=1);

use App\Core\Application;

setlocale(LC_ALL, 'fr_FR');

require_once __DIR__ . "/../vendor/autoload.php";

/** @var Application $app */
$app = require_once __DIR__ . "/../src/start/routes.php";

$app->run();
