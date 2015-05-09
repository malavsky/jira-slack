<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Silex\Application();

require_once __DIR__ . '/../config/services.php';

$app->get('/hello', function () {
    return 'Hello!';
});

$app->post('/send/event/{project}', function (Silex\Application $app, $project) {
    if (!$slackWebHook = $app['projectToSlackWebHook']->getWebHookByProject($project)) {
        return 'Can not find this project in config';
    }

    $webHookAdapter = $app['webHooksAdapter'];
    $webHookAdapter->prepareAndSend($app['request']->getContent(), $slackWebHook);
    return 'Ok';
});

$app->run();
