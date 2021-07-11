<?php

namespace Jooservices\XcrawlerClient\Settings\Traits;

use Faker\Factory;

trait UserAgent
{
    protected function getUserAgent()
    {
        $version = $this->getVersion();
        return "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/{$version}  Safari/537.36";
    }

    protected function getVersion(): string
    {
        $faker = Factory::create();

        $majorVersion = $faker->numberBetween(89, 93);
        $minorVersion = $faker->numberBetween(0, 10);
        $buildVersion = $faker->numberBetween(0, 5000);
        $patchVersion = $faker->numberBetween(0, 999);

        return "{$majorVersion}.{$minorVersion}.{$buildVersion}.{$patchVersion}";
    }
}
