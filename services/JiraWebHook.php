<?php

namespace Services;


class JiraWebHook
{

    const CREATE_EVENT = 'jira:issue_created';
    const UPDATE_EVENT = 'jira:issue_updated';
    const DELETE_EVENT = 'jira:issue_deleted';
    const WORKLOG_UPDATE_EVENT = 'jira:worklog_updated';

    const UPDATE_WITH_COMMENT_TEXT = 'Changed status and added comment by';
    const UPDATE_TEXT = 'Changed status by';
    const COMMENT_TEXT = 'Added comment by';
    const CREATE_TEXT = 'Created issue by';
    const DELETE_TEXT = 'Deleted issue by';

    public function parse($jsonBody)
    {
        if ($jiraData = json_decode($jsonBody)) {
            return $this->prepareData($jiraData);
        }
        return false;
    }

    private function prepareData($jiraData)
    {
        $data = [];

        $data['user']  = $this->getUserData($jiraData);
        $data['issue'] = $this->getIssueData($jiraData);

        switch ($jiraData->webhookEvent) {
            case static::CREATE_EVENT:
                $data['text'] = static::CREATE_TEXT;
                break;
            case static::UPDATE_EVENT:
                $data = $this->prepareDataForUpdateEvent($data, $jiraData);
                break;
            case static::DELETE_EVENT:
                $data['text'] = static::DELETE_TEXT;
                break;
        }

        return $data;
    }

    private function prepareDataForUpdateEvent($data, $jiraData)
    {
        if (isset($jiraData->comment)) {
            $data['comment'] = $this->getCommentData($jiraData);
            $data['text']    = static::COMMENT_TEXT;
        }

        if (isset($jiraData->changelog)) {
            $data['changelog'] = $this->getChangeLogData($jiraData);
            $data['text']      = static::UPDATE_TEXT;
        }

        // Override text
        if (isset($jiraData->comment) && isset($jiraData->changelog)) {
            $data['text'] = static::UPDATE_WITH_COMMENT_TEXT;
        }

        return $data;
    }

    private function getIssueData($jiraData)
    {
        return [
            'number'   => $jiraData->issue->key,
            'link'     => $this->getLink($jiraData->issue->self, $jiraData->issue->key),
            'summary'  => $jiraData->issue->fields->summary,
            'type'     => [
                'name' => $jiraData->issue->fields->issuetype->name,
                'icon' => $jiraData->issue->fields->issuetype->iconUrl
            ],

            'priority' => [
                'name' => $jiraData->issue->fields->priority->name,
                'icon' => $jiraData->issue->fields->priority->iconUrl
            ]
        ];
    }

    private function getCommentData($jiraData)
    {
        return [
            'author' => $jiraData->comment->author->displayName,
            'body'   => $jiraData->comment->body
        ];
    }

    private function getStatusData($jiraData)
    {
        return [
            'name' => $jiraData->issue->fields->status->name,
            'icon' => $jiraData->issue->fields->status->iconUrl
        ];
    }

    private function getUserData($jiraData)
    {
        return [
            'name' => $jiraData->user->displayName
        ];
    }

    private function getChangeLogData($jiraData)
    {
        $items = [];
        foreach ($jiraData->changelog->items as $item) {
            $items[] = (array)$item;
        }

        return [
            'items' => $items
        ];
    }

    private function getLink($url, $issueNumber)
    {
        $parsedUrl = parse_url($url);
        return $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . '/browse/' . $issueNumber;
    }
}