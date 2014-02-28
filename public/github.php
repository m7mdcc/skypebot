<?php
require_once '../vendor/autoload.php';

use Inviqa\SkypeEngine;
use Inviqa\Integration\Github;

$githubHandler = new Github(SkypeEngine::getDbusProxy());
$githubHandler->handle(file_get_contents('php://input'));
