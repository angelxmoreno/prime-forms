<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Forms Model
 *
 * @property \App\Model\Table\SubmissionsTable&\Cake\ORM\Association\HasMany $Submissions
 * @method \App\Model\Entity\Form newEmptyEntity()
 * @method \App\Model\Entity\Form newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Form> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Form get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Form findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Form patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Form> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Form|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Form saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Form>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Form>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Form>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Form> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Form>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Form>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Form>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Form> deleteManyOrFail(iterable $entities, array $options = [])
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class FormsTable extends Table
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

        $this->setTable('forms');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Submissions', [
            'foreignKey' => 'form_id',
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
            ->scalar('slug')
            ->maxLength('slug', 191)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('description')
            ->allowEmptyString('description');

        $validator
            ->scalar('form_class')
            ->maxLength('form_class', 255)
            ->requirePresence('form_class', 'create')
            ->notEmptyString('form_class')
            ->add('form_class', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('schema_path')
            ->maxLength('schema_path', 255)
            ->requirePresence('schema_path', 'create')
            ->notEmptyString('schema_path');

        $validator
            ->boolean('is_active')
            ->notEmptyString('is_active');

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
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']);
        $rules->add($rules->isUnique(['form_class']), ['errorField' => 'form_class']);

        return $rules;
    }
}
