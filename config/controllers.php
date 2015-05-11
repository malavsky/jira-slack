<?php

$app->post('/send/event/{project}', function (Silex\Application $app, $project) {
    if (!$slackWebHook = $app['projectManager']->getWebHookByProject($project)) {
        return 'Can not find this project in config';
    }

    $webHookAdapter = $app['webHooksAdapter'];
    $webHookAdapter->prepareAndSend($app['request']->getContent(), $slackWebHook);
    return 'Ok';
});

$app->get('/project/list/{token}', function (Silex\Application $app, $token) {
    $projectManager = $app['projectManager'];
    checkToken($projectManager, $token);

    $showListBody = $projectManager->showList($app);

    if (!$showListBody) {
        return 'Some error happened';
    }

    return $showListBody;
})->bind('project.list');

$app->get('/project/edit/{name}/{token}', function (Silex\Application $app, $name, $token) {
    $projectManager = $app['projectManager'];
    checkToken($projectManager, $token);

    $editBody = $projectManager->editProject($name, $app);

    if (!$editBody) {
        return 'Some error happened';
    }

    return $editBody;
})->bind('project.edit');

$app->post('/project/edit/{name}/{token}', function (Silex\Application $app, $name, $token) {
    $projectManager = $app['projectManager'];
    checkToken($projectManager, $token);

    $request = $app['request'];
    $webHookUrl = $request->request->get('webHookUrl');

    if (!$webHookUrl) {
        return $projectManager->editProject($name, $app, 'Сan not be empty');
    }

    $status = $projectManager->addUpdateProject($name, $webHookUrl);

    if ($status) {
        return $app->redirect($app['url_generator']->generate('project.list', ['token' => $token]));
    }

    return 'Some error happened';
});

$app->get('/project/create/{token}', function (Silex\Application $app, $token) {
    $projectManager = $app['projectManager'];
    checkToken($projectManager, $token);

    $html = $projectManager->createProject($app);
    if (!$html) {
        return 'Some error happened';
    }

    return $html;
})->bind('project.create');

$app->post('/project/create/{token}', function (Silex\Application $app, $token) {
    $projectManager = $app['projectManager'];
    checkToken($projectManager, $token);

    $request = $app['request'];
    $webHookUrl = $request->request->get('webHookUrl');
    $projectName = $request->request->get('projectName');
    $error = '';

    if (!$webHookUrl) {
        $error .= 'Webhook can not be empty <br>';
    }

    if (!$projectName) {
        $error .= 'Project Name can not be empty <br>';
    }

    if ($projectManager->getWebHookByProject($projectName)) {
        $error .= 'Project with name "' . $projectName . '" already exist';
    }

    if ($error) {
        return $projectManager->createProject($app, $projectName, $webHookUrl, $error);
    }

    $status = $projectManager->addUpdateProject($projectName, $webHookUrl);

    if ($status) {
        return $app->redirect($app['url_generator']->generate('project.list', ['token' => $token]));
    }

    return 'Some error happened';

});

$app->get('/project/create/{name}/{token}', function (Silex\Application $app, $name, $token) {
    $projectManager = $app['projectManager'];
    checkToken($projectManager, $token);

    $status = $projectManager->deleteProject($name);

    if ($status) {
        return $app->redirect($app['url_generator']->generate('project.list', ['token' => $token]));
    }

    return 'Some error happened';

})->bind('project.delete');


function checkToken($projectManager, $token) {
    if (!$projectManager->checkToken($token)) {
        return 'Token is invalid. Please check your security token.';
    }
}