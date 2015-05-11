<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__ . '/../config/services.php';
require_once __DIR__ . '/../config/controllers.php';

$app->run();
