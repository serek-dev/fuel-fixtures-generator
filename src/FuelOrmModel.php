<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator;

use Orm\Model as BaseFuelOrm;
use Stwarog\Uow\Utils\ReflectionHelper;

final class FuelOrmModel implements Model
{
    private const NATIVE_FUEL_RELATIONS = ['_belongs_to', '_has_one', '_has_many'];

    private BaseFuelOrm $orm;

    public function __construct(BaseFuelOrm $model)
    {
        $this->orm = $model;
    }

    public function getClass(): string
    {
        return get_class($this->orm);
    }

    public function hasRelations(): bool
    {
        return !empty($this->getRelations());
    }

    public function getProperties(): array
    {
        $mapped = [];
        $original = ReflectionHelper::getValue($this->orm, '_properties');

        foreach ($original as $key => $value) {
            if (is_array($value)) {
                $mapped[] = $key;
                continue;
            }
            $mapped[] = $value;
        }

        return $mapped;
    }

    /**
     * @return array<string, array{target: string, has_many: string}>
     */
    public function getRelations(): array
    {
        $relations = [];

        // In native and new approach way
        foreach (self::NATIVE_FUEL_RELATIONS as $relation) {
            $hasMany = $relation === '_has_many';
            foreach (ReflectionHelper::getValue($this->orm, $relation) as $field => $data) {
                $relations[$field] = ['target' => $data['model_to'], 'has_many' => $hasMany];
            }
        }

        return $relations;
    }
}
