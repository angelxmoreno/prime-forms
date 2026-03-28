<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
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
                'appwrite_user_id' => 'Lorem ipsum dolor sit amet',
                'email' => 'Lorem ipsum dolor sit amet',
                'display_name' => 'Lorem ipsum dolor sit amet',
                'email_verified' => 1,
                'last_login_at' => '2026-03-28 01:39:20',
                'created' => '2026-03-28 01:39:20',
                'modified' => '2026-03-28 01:39:20',
            ],
        ];
        parent::init();
    }
}
