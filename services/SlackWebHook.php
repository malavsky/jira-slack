<?php

namespace services;

use GuzzleHttp\Client;

class SlackWebHook
{
    const ICON_URL = 'https://slack.global.ssl.fastly.net/12d4/img/services/jira_48.png';
    const BOT_NAME = 'Jira';

    public function send($slackWebHook, $data)
    {
        $client  = new Client(['base_url' => $slackWebHook]);
        $message = $this->createMessage($data);
        $client->post(null, ['body' => ['payload' => $message]]);
    }

    private function createMessage($data)
    {
        $message             = [];
        $message['username'] = static::BOT_NAME;
        $message['icon_url'] = static::ICON_URL;

        $message['attachments'][] = [
            'fallback'  => $this->createFallbackMessage($data),
            'pretext'   => $this->createPreText($data),
            'fields'    => $this->createFields($data),
            'color'     => '#205081',
            'mrkdwn_in' => ['fields']
        ];

        return json_encode($message);
    }

    private function createFallbackMessage($data)
    {
        return $data['issue']['number'] . ': ' . $data['issue']['number'] . ' - ' . $data['issue']['summary'] .
        '. ' . $data['text'] . '. - ' . $data['issue']['link'];
    }

    private function createPreText($data)
    {
        $pretext = '<' . $data['issue']['link'] . '|' . $data['issue']['number'] . ': ' . $data['issue']['summary'] . '>. ';
        $pretext .= $data['text'] . ' ' . $data['user']['name'] . '.';

        return $pretext;
    }

    private function createFields($data)
    {
        $fields = [];

        if (isset($data['comment'])) {
            $fields[] = [
                'title' => 'Commented by ' . $data['comment']['author'],
                'value' => $data['comment']['body']
            ];
        }

        if (isset($data['changelog'])) {
            foreach ($data['changelog']['items'] as $item) {
                $fields[] = [
                    'title' => 'Previous ' . $item['field'],
                    'value' => $item['fromString'],
                    'short' => true
                ];

                $fields[] = [
                    'title' => 'New ' . $item['field'],
                    'value' => $item['toString'],
                    'short' => true
                ];
            }

        }

        return $fields;
    }
}
