<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * FormsFixture
 */
class FormsFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'slug' => 'contact-form',
                'title' => 'Contact Form',
                'description' => 'Default contact form used for fixture-backed tests.',
                'form_class' => 'ContactForm',
                'schema_path' => 'project-docs/forms/contact-form.md',
                'is_active' => 1,
                'created' => '2026-03-28 02:00:58',
                'modified' => '2026-03-28 02:00:58',
            ],
        ];
        parent::init();
    }
}
