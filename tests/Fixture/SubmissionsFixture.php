<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * SubmissionsFixture
 */
class SubmissionsFixture extends TestFixture
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
                'form_id' => 1,
                'payload' => '{"name":"Test User","email":"test@example.com","message":"Hello from the fixture."}',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36',
                'referrer_url' => 'https://example.com/contact',
                'accept_language' => 'en-US,en;q=0.9',
                'source_url' => 'https://example.com/forms/contact',
                'reviewed' => 0,
                'review_notes' => null,
                'created' => '2026-03-28 02:01:03',
                'modified' => '2026-03-28 02:01:03',
            ],
        ];
        parent::init();
    }
}
