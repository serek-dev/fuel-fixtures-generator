<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator;

final class FuelAwareNameGenerator implements NameGenerator
{
    public const CLASS_SUFFIX = 'Fixture';
    private const DELIMITER = '\\';

    private Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function getFullName(string $class): string
    {
        $isFuelName = strpos($class, '_') !== false;
        $class = str_replace('_', '', $class);

        $namespace = $isFuelName ? $this->config->getNamespace() : '';

        $fullName = $namespace . self::DELIMITER . $class . self::CLASS_SUFFIX;

        return $this->normalize($fullName);
    }

    public function getShortName(string $class): string
    {
        $fullName = $this->getFullName($class);
        $chunks = explode(self::DELIMITER, $fullName);

        $className = $chunks[count($chunks) - 1];

        return $this->normalize($className);
    }

    private function normalize(string $name): string
    {
        $firstLetter = substr($name, 0, 1);

        if ($firstLetter !== self::DELIMITER) {
            return self::DELIMITER . $name;
        }

        return $name;
    }
}
