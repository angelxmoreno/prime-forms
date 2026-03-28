<?php
declare(strict_types=1);

use Migrations\BaseMigration;

class Initial extends BaseMigration
{
    public bool $autoId = false;

    /**
     * Up Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-up-method
     *
     * @return void
     */
    public function up(): void
    {
        $isSqlite = $this->getAdapter()->getAdapterType() === 'sqlite';
        $shouldCreateSubmissionForeignKey = $this->getAdapter()->getAdapterType() !== 'sqlite';
        $idColumnType = $isSqlite ? 'integer' : 'biginteger';
        $unsignedIds = !$isSqlite;

        $this->table('database_logs')
            ->addColumn('id', 'integer', [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('type', 'string', [
                'default' => null,
                'limit' => 50,
                'null' => false,
            ])
            ->addColumn('summary', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('message', 'text', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('context', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('ip', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('hostname', 'string', [
                'default' => null,
                'limit' => 100,
                'null' => true,
            ])
            ->addColumn('uri', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('refer', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('user_agent', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('count', 'integer', [
                'default' => '0',
                'limit' => null,
                'null' => false,
                'signed' => true,
            ])
            ->addIndex(
                $this->index('type')
                    ->setName('type_idx')
            )
            ->addIndex(
                $this->index('created')
                    ->setName('created_idx')
            )
            ->addIndex(
                $this->index([
                        'type',
                        'created',
                    ])
                    ->setName('type_created_idx')
            )
            ->create();

        $this->table('forms')
            ->addColumn('id', $idColumnType, [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => $unsignedIds ? false : true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('slug', 'string', [
                'default' => null,
                'limit' => 191,
                'null' => false,
            ])
            ->addColumn('title', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('description', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('form_class', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('schema_path', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('is_active', 'boolean', [
                'default' => true,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index('slug')
                    ->setName('forms_slug_unique')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('form_class')
                    ->setName('forms_form_class_unique')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('is_active')
                    ->setName('forms_is_active_idx')
            )
            ->create();

        $this->table('submissions')
            ->addColumn('id', $idColumnType, [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => $unsignedIds ? false : true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('form_id', $idColumnType, [
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => $unsignedIds ? false : true,
            ])
            ->addColumn('payload', 'json', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('ip_address', 'string', [
                'default' => null,
                'limit' => 45,
                'null' => true,
            ])
            ->addColumn('user_agent', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('referrer_url', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('accept_language', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => true,
            ])
            ->addColumn('source_url', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('reviewed', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('review_notes', 'text', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index('reviewed')
                    ->setName('submissions_reviewed_idx')
            )
            ->addIndex(
                $this->index([
                        'form_id',
                        'created',
                    ])
                    ->setName('submissions_form_created_idx')
            )
            ->create();

        $this->table('users')
            ->addColumn('id', $idColumnType, [
                'autoIncrement' => true,
                'default' => null,
                'limit' => null,
                'null' => false,
                'signed' => $unsignedIds ? false : true,
            ])
            ->addPrimaryKey(['id'])
            ->addColumn('appwrite_user_id', 'string', [
                'default' => null,
                'limit' => 64,
                'null' => false,
            ])
            ->addColumn('email', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('display_name', 'string', [
                'default' => null,
                'limit' => 255,
                'null' => false,
            ])
            ->addColumn('email_verified', 'boolean', [
                'default' => false,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('last_login_at', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => true,
            ])
            ->addColumn('created', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addColumn('modified', 'datetime', [
                'default' => null,
                'limit' => null,
                'null' => false,
            ])
            ->addIndex(
                $this->index('appwrite_user_id')
                    ->setName('users_appwrite_user_id_unique')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('email')
                    ->setName('users_email_unique')
                    ->setType('unique')
            )
            ->addIndex(
                $this->index('last_login_at')
                    ->setName('users_last_login_at_idx')
            )
            ->create();

        if ($shouldCreateSubmissionForeignKey) {
            $this->table('submissions')
                ->addForeignKey(
                    $this->foreignKey('form_id')
                        ->setReferencedTable('forms')
                        ->setReferencedColumns('id')
                        ->setOnDelete('RESTRICT')
                        ->setOnUpdate('CASCADE')
                        ->setName('submissions_form_id_fk')
                )
                ->update();
        }

    }

    /**
     * Down Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-down-method
     *
     * @return void
     */
    public function down(): void
    {
        if ($this->getAdapter()->getAdapterType() !== 'sqlite') {
            $this->table('submissions')
                ->dropForeignKey(
                    'form_id'
                )->save();
        }

        $this->table('database_logs')->drop()->save();
        $this->table('forms')->drop()->save();
        $this->table('submissions')->drop()->save();
        $this->table('users')->drop()->save();
    }
}
