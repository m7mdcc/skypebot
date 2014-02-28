<?php
require_once '../vendor/autoload.php';

use Inviqa\SkypeEngine;
use Inviqa\Integration\Jenkins;

$jenkinsHandler = new Jenkins(SkypeEngine::getDbusProxy());

try {
    $jenkinsHandler->handle(file_get_contents('php://input'));
} catch (\Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
