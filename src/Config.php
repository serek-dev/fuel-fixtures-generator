<?php

namespace Stwarog\FuelFixturesGenerator;

interface Config
{
    public function getNamespace(): string;

    public function storagePath(): string;

    public function outputTemplate(): string;
}
