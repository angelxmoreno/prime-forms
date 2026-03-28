<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FormsTable;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FormsTable Test Case
 */
class FormsTableTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\Model\Table\FormsTable
     */
    protected $Forms;

    /**
     * Fixtures
     *
     * @var array<string>
     */
    protected array $fixtures = [
        'app.Forms',
        'app.Submissions',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $config = $this->getTableLocator()->exists('Forms') ? [] : ['className' => FormsTable::class];
        $this->Forms = $this->getTableLocator()->get('Forms', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Forms);

        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     * @link \App\Model\Table\FormsTable::validationDefault()
     */
    public function testValidationDefault(): void
    {
        $valid = $this->Forms->newEntity([
            'slug' => 'feedback-form',
            'title' => 'Feedback Form',
            'description' => 'Collects product feedback.',
            'form_class' => 'FeedbackForm',
            'schema_path' => 'project-docs/forms/feedback-form.md',
            'is_active' => false,
        ]);
        $this->assertEmpty($valid->getErrors());

        $invalid = $this->Forms->newEntity([
            'slug' => '',
            'title' => '',
            'form_class' => '',
            'schema_path' => '',
        ]);
        $errors = $invalid->getErrors();

        $this->assertArrayHasKey('slug', $errors);
        $this->assertArrayHasKey('title', $errors);
        $this->assertArrayHasKey('form_class', $errors);
        $this->assertArrayHasKey('schema_path', $errors);
    }

    /**
     * Test buildRules method
     *
     * @return void
     * @link \App\Model\Table\FormsTable::buildRules()
     */
    public function testBuildRules(): void
    {
        $duplicateSlug = $this->Forms->newEntity([
            'slug' => 'contact-form',
            'title' => 'Another Contact Form',
            'form_class' => 'AnotherContactForm',
            'schema_path' => 'project-docs/forms/another-contact-form.md',
            'is_active' => true,
        ], ['validate' => false]);
        $this->assertFalse($this->Forms->save($duplicateSlug));
        $this->assertNotEmpty($duplicateSlug->getErrors()['slug'] ?? []);

        $duplicateFormClass = $this->Forms->newEntity([
            'slug' => 'newsletter-form',
            'title' => 'Newsletter Form',
            'form_class' => 'ContactForm',
            'schema_path' => 'project-docs/forms/newsletter-form.md',
            'is_active' => true,
        ], ['validate' => false]);
        $this->assertFalse($this->Forms->save($duplicateFormClass));
        $this->assertNotEmpty($duplicateFormClass->getErrors()['form_class'] ?? []);
    }
}
