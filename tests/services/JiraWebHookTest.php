<?php
namespace tests\services;

use Aivus\TestHelper\TestHelper;
use PHPUnit_Framework_TestCase;
use Services\JiraWebHook;

class JiraWebHookTest extends PHPUnit_Framework_TestCase
{
    /** @var JiraWebHook */
    protected $jiraWebHook;

    /** @var TestHelper */
    private $testHelper;

    protected function setUp()
    {
        parent::setUp();
        $this->testHelper  = new TestHelper();
        $this->jiraWebHook = new JiraWebHook();
    }

    /**
     * @dataProvider providerPrepareData
     *
     * @param string $data
     * @param array  $expected
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
                file_get_contents(__DIR__ . '/../fixtures/issue_commented.json'),
                require __DIR__ . '/../fixtures/issue_commented_expected.php'
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

    /**
     * @dataProvider providerTestGetLink
     *
     * @param $parameters
     * @param $expectedResult
     */
    public function testGetLink($parameters, $expectedResult)
    {
        $actualResult = $this->testHelper->invokeMethod($this->jiraWebHook, 'getLink', $parameters);
        $this->assertEquals($expectedResult, $actualResult);
    }

    public function providerTestGetLink()
    {
        return [
            [
                [
                    'http://jira.example.com/somepath',
                    'PROJECT-111',
                ],
                'http://jira.example.com/browse/PROJECT-111'
            ],
            [
                [
                    'https://jira.example.com/somepath',
                    'PROJECT-111',
                ],
                'https://jira.example.com/browse/PROJECT-111'
            ],
            [
                [
                    'http://jira.example.com:8080/somepath',
                    'PROJECT-111',
                ],
                'http://jira.example.com:8080/browse/PROJECT-111'
            ]
        ];
    }
}
