<?php

declare(strict_types=1);

namespace Mock;

use Orm\Model;

/**
 * @property int $id
 * @property string $status
 * @property string $body
 * @property int $related_id
 * @property ?object $relation
 * @property array<object> $relation_many
 */
final class ModelImitation extends Model
{
    public const PROPERTIES = ['id', 'status', 'body', 'related_id'];

    protected static $_properties = self::PROPERTIES;

    protected static array $_belongs_to = [
        'belongs_to' => [
            'key_from' => 'related_id',
            'model_to' => ModelImitation::class,
            'key_to' => 'id',
        ],
    ];

    protected static array $_has_one = [
        'has_one' => [
            'key_from' => 'id',
            'model_to' => ModelImitation::class,
            'key_to' => 'related_id',
        ],
    ];
}
