<?php

namespace services;


use Silex\Application;
use Symfony\Component\Yaml\Yaml;

class ProjectManager {

    private $symfonyYml;
    private $projects;
    private $path = '/../config/yml/projectToSlackWebHook.yml';
    private $configPath = '/../config/yml/config.yml';
    private $securityToken;

    public function __construct(Yaml $symfonyYml)
    {
        $this->symfonyYml = $symfonyYml;
        $this->projects = $symfonyYml->parse(__DIR__ . $this->path)['projects'];
        $this->securityToken = $symfonyYml->parse(__DIR__ . $this->configPath)['securityToken'];

    }

    public function getWebHookByProject($project)
    {
        if (isset($this->projects[$project])) {
            return $this->projects[$project];
        }
        return null;
    }

    public function checkToken($token)
    {
        return ($token === $this->securityToken);
    }

    public function showList(Application $app)
    {
        $createNewUrl = $app['url_generator']->generate('project.create', ['token' => $this->securityToken]);
        $html = '<a href = "' . $createNewUrl .'">Add new</a><br>';
        if ($this->projects && !empty($this->projects) && is_array($this->projects)) {
            $html .= '<ul>';
            foreach ($this->projects as $projectName => $slackWebHookUrl) {
                $editUrl = $app['url_generator']->generate('project.edit', ['token' => $this->securityToken, 'name' => $projectName]);
                $deleteUrl = $app['url_generator']->generate('project.delete', ['token' => $this->securityToken, 'name' => $projectName]);
                $html .= '<li>' . $projectName . ' : ' . $slackWebHookUrl ;
                $html .= ' <a href = "' . $editUrl .'">Edit</a>';
                $html .= ' <a href = "' . $deleteUrl .'">Delete</a>';
            }
            $html .= '<ul>';
        }
        return $html;
    }

    public function editProject($name, $app, $error = null)
    {
        $url = $app['url_generator']->generate(
            'project.edit',
            [
                'name' => $name,
                'token' => $this->securityToken
            ]
        );

        $html = '<form action="' . $url . '" method="POST">';
        $html .= '<input type="text" value="' . $this->getWebHookByProject($name) . '" size="100" name="webHookUrl" required>';
        $html .= '<button type="submit" value="Save">Save</button>';
        $html .= '</form>';

        if ($error) {
            $html .= '<p>'. $error .'</p>';
        }

        return $html;
    }

    public function createProject($app, $name = '', $webHookUrl = '', $error = null)
    {
        $html = '';
        $url = $app['url_generator']->generate(
            'project.create',
            [
                'token' => $this->securityToken
            ]
        );
        if ($error) {
            $html .= '<p>'. $error .'</p>';
        }
        $html .= '<form action="' . $url . '" method="POST">';
        $html .= '<label for="projectName">Project Name</label> ';
        $html .= '<input type="text" value="' . $name . '" size="20" name="projectName" required><br>';
        $html .= '<label for="webHookUrl">Slack Webhook Url</label> ';
        $html .= '<input type="text" value="' . $webHookUrl . '" size="100" name="webHookUrl" required><br>';
        $html .= '<button type="submit" value="Save">Save</button>';
        $html .= '</form>';

        return $html;
    }

    private function writeProjectFile()
    {
        $dump = $this->symfonyYml->dump(['projects' => $this->projects]);
        $status = file_put_contents(__DIR__ . $this->path, $dump);
        return ($status) ? true : false;
    }

    public function addUpdateProject($name, $webHookUrl)
    {
        $this->projects[$name] = $webHookUrl;
        $status = $this->writeProjectFile();
        return $status;
    }

    public function deleteProject($name)
    {
        unset($this->projects[$name]);
        $status = $this->writeProjectFile();
        return $status;
    }

}