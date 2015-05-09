<?php

use Services\ProjectToSlackWebHookResolver;
use Services\JiraWebHook;
use Services\SlackWebHook;
use Services\WebHookAdapter;

$app['projectToSlackWebHook'] = $app->share(function () {
    return new ProjectToSlackWebHookResolver(new Symfony\Component\Yaml\Yaml());
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
