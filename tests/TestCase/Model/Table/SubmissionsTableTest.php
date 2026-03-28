<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\SubmissionsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\SubmissionsTable Test Case
 */
class SubmissionsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\SubmissionsTable
     */
    protected $Submissions;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Submissions',
        'app.Forms',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Submissions') ? [] : ['className' => SubmissionsTable::class];
        $this->Submissions = $this->getTableLocator()->get('Submissions', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Submissions);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\SubmissionsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $missingFormId = $this->Submissions->newEntity([
            'payload' => '{"message":"Missing form id"}',
        ]);
        $this->assertNotEmpty($missingFormId->getErrors()['form_id'] ?? []);

        $missingPayload = $this->Submissions->newEntity([
            'form_id' => 1,
        ]);
        $this->assertNotEmpty($missingPayload->getErrors()['payload'] ?? []);

        $invalidFormId = $this->Submissions->newEntity([
            'form_id' => '',
            'payload' => '{"message":"Invalid form id"}',
        ]);
        $this->assertNotEmpty($invalidFormId->getErrors()['form_id'] ?? []);

        $valid = $this->Submissions->newEntity([
            'form_id' => 1,
            'payload' => '{"message":"Looks good"}',
            'reviewed' => false,
        ]);
        $this->assertEmpty($valid->getErrors());
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\SubmissionsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $invalidSubmission = $this->Submissions->newEntity([
            'form_id' => 9999,
            'payload' => '{"message":"Unknown form"}',
        ]);
        $this->assertFalse($this->Submissions->save($invalidSubmission));
        $this->assertNotEmpty($invalidSubmission->getErrors()['form_id'] ?? []);

        $validSubmission = $this->Submissions->newEntity([
            'form_id' => 1,
            'payload' => '{"message":"Valid form reference"}',
            'reviewed' => false,
        ]);
        $this->assertNotFalse($this->Submissions->save($validSubmission));
    }
}
