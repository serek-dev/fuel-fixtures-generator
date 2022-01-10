<?php

namespace Stwarog\FuelFixturesGenerator;

interface Config
{
    public function getNameSpace(): string;

    public function storagePath(): string;

    public function outputTemplate(): string;
}
