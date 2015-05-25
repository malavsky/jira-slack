<?php
return [
    'user'      => [
        'name' => 'iantipenko'
    ],
    'issue'     => [
        'number'   => 'PASS-3',
        'link'     => 'http://jira.example.com/browse/PASS-3',
        'summary'  => 'Test summary',
        'type'     => [
            'name' => 'Story',
            'icon' => 'http://jira.example.com/images/icons/ico_story.png'
        ],
        'priority' => [
            'name' => 'Major',
            'icon' => 'http://jira.example.com/images/icons/priorities/major.png'
        ]
    ],
    'comment'   => [
        'author' => 'iantipenko',
        'body'   => 'Example comment'
    ],
    'changelog' => [
        'items' => [
            [
                'field'      => 'status',
                'fieldtype'  => 'jira',
                'from'       => 4,
                'fromString' => 'Reopened',
                'to'         => 5,
                'toString'   => 'Resolved'
            ],
            [
                'field'      => 'resolution',
                'fieldtype'  => 'jira',
                'from'       => '',
                'fromString' => '',
                'to'         => 1,
                'toString'   => 'Fixed'
            ]
        ]
    ],
    'text'      => 'Changed status and added comment by'
];
