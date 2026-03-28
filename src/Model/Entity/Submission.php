<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Submission Entity
 *
 * @property int $id
 * @property int $form_id
 * @property array $payload
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property string|null $referrer_url
 * @property string|null $accept_language
 * @property string|null $source_url
 * @property bool $reviewed
 * @property string|null $review_notes
 * @property \Cake\I18n\DateTime $created
 * @property \Cake\I18n\DateTime $modified
 *
 * @property \App\Model\Entity\Form $form
 */
class Submission extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'form_id' => true,
        'payload' => true,
        'ip_address' => true,
        'user_agent' => true,
        'referrer_url' => true,
        'accept_language' => true,
        'source_url' => true,
        'reviewed' => true,
        'review_notes' => true,
        'created' => true,
        'modified' => true,
        'form' => true,
    ];
}
