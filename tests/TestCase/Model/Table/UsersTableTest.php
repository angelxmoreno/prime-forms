<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\UsersTable Test Case
 */
class UsersTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\UsersTable
     */
    protected $Users;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Users') ? [] : ['className' => UsersTable::class];
        $this->Users = $this->getTableLocator()->get('Users', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Users);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\UsersTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $missingRequired = $this->Users->newEntity([]);
        $errors = $missingRequired->getErrors();

        $this->assertArrayHasKey('appwrite_user_id', $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('display_name', $errors);

        $invalidEmail = $this->Users->newEntity([
            'appwrite_user_id' => 'user_01HQ5Y8V4QWERTY123456789ZZ',
            'email' => 'not-an-email',
            'display_name' => 'Invalid User',
            'email_verified' => false,
        ]);
        $this->assertNotEmpty($invalidEmail->getErrors()['email'] ?? []);

        $tooLongEmail = $this->Users->newEntity([
            'appwrite_user_id' => 'user_01HQ5Y8V4QWERTY123456789XY',
            'email' => str_repeat('a', 244) . '@example.com',
            'display_name' => 'Long Email User',
            'email_verified' => false,
        ]);
        $this->assertNotEmpty($tooLongEmail->getErrors()['email'] ?? []);
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\UsersTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $duplicateAppwriteId = $this->Users->newEntity([
            'appwrite_user_id' => 'user_01HQ5Y8V4QWERTY123456789AB',
            'email' => 'unique@example.com',
            'display_name' => 'Duplicate Appwrite User',
            'email_verified' => false,
        ]);
        $this->assertFalse($this->Users->save($duplicateAppwriteId));
        $this->assertNotEmpty($duplicateAppwriteId->getErrors()['appwrite_user_id'] ?? []);

        $duplicateEmail = $this->Users->newEntity([
            'appwrite_user_id' => 'user_01HQ5Y8V4QWERTY123456789CD',
            'email' => 'test.user+1@example.com',
            'display_name' => 'Duplicate Email User',
            'email_verified' => false,
        ]);
        $this->assertFalse($this->Users->save($duplicateEmail));
        $this->assertNotEmpty($duplicateEmail->getErrors()['email'] ?? []);
    }
}
