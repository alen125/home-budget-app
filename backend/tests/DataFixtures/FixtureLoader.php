<?php

declare(strict_types=1);

namespace App\Tests\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Fidry\AliceDataFixtures\LoaderInterface;

class FixtureLoader extends Fixture
{
    public function __construct(
        private readonly LoaderInterface $loader,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $this->loader->load(
            glob(__DIR__ . '/Fixtures/*.yaml'),
        );
    }
}