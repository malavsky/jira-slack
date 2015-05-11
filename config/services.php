<?php

use Services\ProjectManager;
use Services\JiraWebHook;
use Services\SlackWebHook;
use Services\WebHookAdapter;

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['projectManager'] = $app->share(function () {
    return new ProjectManager(new Symfony\Component\Yaml\Yaml());
});

$app['jiraWebHook'] = $app->share(function () {
    return new JiraWebHook();
});

$app['slackWebHook'] = $app->share(function () {
    return new SlackWebHook();
});

$app['webHooksAdapter'] = $app->share(function ($app) {
    return new WebHookAdapter($app['jiraWebHook'], $app['slackWebHook']);
});