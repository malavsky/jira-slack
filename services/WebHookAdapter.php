<?php

namespace Services;


class WebHookAdapter
{

    private $jiraWebHook;
    private $slackWebHook;

    public function __construct(JiraWebHook $jiraWebHook, SlackWebHook $slackWebHook)
    {
        $this->jiraWebHook = $jiraWebHook;
        $this->slackWebHook = $slackWebHook;
    }

    public function prepareAndSend($jiraContentBody, $slackWebHook)
    {
        $preparedData = $this->jiraWebHook->parse($jiraContentBody);

        if (!$preparedData) {
            throw new \ErrorException('Data not valid');
        }

        $this->slackWebHook->send($slackWebHook, $preparedData);
    }
}