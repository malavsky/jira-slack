<?php

namespace services;


use Symfony\Component\Yaml\Yaml;

class ProjectToSlackWebHookResolver {

    private $symfonyYml;
    private $projects;
    private $path = '/../config/yml/projectToSlackWebHook.yml';

    public function __construct(Yaml $symfonyYml)
    {
        $this->symfonyYml = $symfonyYml;
        $this->projects = $symfonyYml->parse(__DIR__ . $this->path)['projects'];
    }

    public function getWebHookByProject($project)
    {
        if (isset($this->projects[$project])) {
            return $this->projects[$project];
        }
        return null;
    }
}