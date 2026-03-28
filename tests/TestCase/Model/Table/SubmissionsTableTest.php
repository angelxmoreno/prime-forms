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
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\SubmissionsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
