<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Submissions Model
 *
 * @property \App\Model\Table\FormsTable&\Cake\ORM\Association\BelongsTo $Forms
 * @method \App\Model\Entity\Submission newEmptyEntity()
 * @method \App\Model\Entity\Submission newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Submission> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Submission get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Submission findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Submission patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Submission> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Submission|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Submission saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Submission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Submission>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Submission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Submission> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Submission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Submission>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Submission>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Submission> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SubmissionsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('submissions');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Forms', [
            'foreignKey' => 'form_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('form_id');

        $validator
            ->requirePresence('payload', 'create')
            ->notEmptyString('payload');

        $validator
            ->scalar('ip_address')
            ->maxLength('ip_address', 45)
            ->allowEmptyString('ip_address');

        $validator
            ->scalar('user_agent')
            ->allowEmptyString('user_agent');

        $validator
            ->scalar('referrer_url')
            ->allowEmptyString('referrer_url');

        $validator
            ->scalar('accept_language')
            ->maxLength('accept_language', 255)
            ->allowEmptyString('accept_language');

        $validator
            ->scalar('source_url')
            ->allowEmptyString('source_url');

        $validator
            ->boolean('reviewed')
            ->notEmptyString('reviewed');

        $validator
            ->scalar('review_notes')
            ->allowEmptyString('review_notes');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['form_id'], 'Forms'), ['errorField' => 'form_id']);

        return $rules;
    }
}
