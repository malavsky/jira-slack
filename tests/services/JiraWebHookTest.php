<?php
namespace tests\services;

use PHPUnit_Framework_TestCase;
use Services\JiraWebHook;

class JiraWebHookTest extends PHPUnit_Framework_TestCase
{
    /** @var JiraWebHook */
    protected $jiraWebHook;

    protected function setUp()
    {
        parent::setUp();
        $this->jiraWebHook = new JiraWebHook();
    }

    /**
     * @dataProvider providerPrepareData
     * @param string $data
     */
    public function testPrepareData($data, $expected)
    {
        $actual = $this->jiraWebHook->parse($data);
        $this->assertEquals($expected, $actual);
    }

    public function providerPrepareData()
    {
        return [
            [
                file_get_contents(__DIR__ . '/../fixtures/issue_created.json'),
                require __DIR__ . '/../fixtures/issue_created_expected.php'
            ],
            [
                file_get_contents(__DIR__ . '/../fixtures/issue_updated.json'),
                require __DIR__ . '/../fixtures/issue_updated_expected.php'
            ],
            [
                file_get_contents(__DIR__ . '/../fixtures/issue_updated_with_comment.json'),
                require __DIR__ . '/../fixtures/issue_updated_with_comment_expected.php'
            ],
            [
                file_get_contents(__DIR__ . '/../fixtures/issue_deleted.json'),
                require __DIR__ . '/../fixtures/issue_deleted_expected.php'
            ],
            [
                'invalid data',
                false
            ]
        ];
    }
}
