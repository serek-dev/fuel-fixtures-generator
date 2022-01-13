<?php

declare(strict_types=1);

namespace Stwarog\FuelFixturesGenerator;

use Stwarog\FuelFixtures\Fuel\Factory as FixtureBaseFactory;
use Stwarog\FuelFixturesGenerator\States\Callback;
use Stwarog\FuelFixturesGenerator\States\Reference;

final class FixtureFactory implements Factory
{
    private NameGenerator $name;
    private Config $config;

    public function __construct(NameGenerator $name, Config $config)
    {
        $this->name = $name;
        $this->config = $config;
    }

    public function create(Model $adapted): FixtureChunk
    {
        $fixture = new FixtureChunk();
        $fixture->namespace = $this->config->getNameSpace();
        $fixture->class = $adapted->getClass();
        $fixture->name = str_replace('\\', '', $this->name->getShortName($adapted->getClass()));

        # if model has at least one relation, then we have to handle it's nested relations
        if ($adapted->hasRelations()) {
            $fixture->addState(new Callback(FixtureBaseFactory::BASIC, FixtureBaseFactory::BASIC));
        }

        # Model relations must be mapped to Fixture`s syntax (property name => nested relation fixture class)
        # each relation must be mapped to proper state for getStates method as well
        foreach ($adapted->getRelations() as $property => $data) {
            $target = $this->name->getShortName($data['target']);
            $fixture->addState(new Reference($property, $property, $target, $data['has_many']));
        }

        # we do not want to have relation FK props here, as Fixture package works using OOP approach
        $fixture->properties = array_filter(
            $adapted->getProperties(),
            fn(string $p) => strpos($p, '_id') === false && $p !== 'id'
        );

        return $fixture;
    }
}
