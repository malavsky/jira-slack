<?php
return [
    'user'      => [
        'name' => 'iantipenko'
    ],
    'issue'     => [
        'number'   => 'PAS-3',
        'link'     => 'http://jira.example.com/browse/PAS-3',
        'summary'  => 'Test2',
        'type'     => [
            'name' => 'Bug',
            'icon' => 'http://jira.example.com/images/icons/issuetypes/bug.png'
        ],
        'priority' => [
            'name' => 'Major',
            'icon' => 'http://jira.example.com/images/icons/priorities/major.png'
        ]
    ],
    'changelog' => [
        'items' => [
            [
                'field'      => 'status',
                'fieldtype'  => 'jira',
                'from'       => 1,
                'fromString' => 'Open',
                'to'         => 3,
                'toString'   => 'In Progress'
            ],
            [
                'field'      => 'assignee',
                'fieldtype'  => 'jira',
                'from'       => 'root',
                'fromString' => 'root',
                'to'         => 'iantipenko',
                'toString'   => 'iantipenko'
            ]
        ]
    ],
    'text'      => 'Changed status by'
];
