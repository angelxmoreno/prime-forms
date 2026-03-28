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
                'slug' => 'Lorem ipsum dolor sit amet',
                'title' => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'form_class' => 'Lorem ipsum dolor sit amet',
                'schema_path' => 'Lorem ipsum dolor sit amet',
                'is_active' => 1,
                'created' => '2026-03-28 02:00:58',
                'modified' => '2026-03-28 02:00:58',
            ],
        ];
        parent::init();
    }
}
