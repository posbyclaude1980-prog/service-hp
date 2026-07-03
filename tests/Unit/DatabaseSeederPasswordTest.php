<?php

namespace Tests\Unit;

use Database\Seeders\DatabaseSeeder;
use ReflectionMethod;
use Tests\TestCase;

class DatabaseSeederPasswordTest extends TestCase
{
    public function test_it_uses_environment_password_when_configured(): void
    {
        putenv('SEED_DEFAULT_PASSWORD=StrongP@ssw0rd123!');

        $seeder = new DatabaseSeeder();
        $method = new ReflectionMethod($seeder, 'resolveDefaultSeedPassword');
        $method->setAccessible(true);

        $this->assertSame('StrongP@ssw0rd123!', $method->invoke($seeder));

        putenv('SEED_DEFAULT_PASSWORD');
    }

    public function test_it_requires_a_password_in_production(): void
    {
        putenv('SEED_DEFAULT_PASSWORD');

        $seeder = new DatabaseSeeder();
        $method = new ReflectionMethod($seeder, 'resolveDefaultSeedPassword');
        $method->setAccessible(true);

        $this->expectException(\RuntimeException::class);
        $method->invoke($seeder, 'production');
    }
}
