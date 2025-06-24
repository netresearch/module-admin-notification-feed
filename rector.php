<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;
use Rector\Php81\Rector\ClassMethod\NewInInitializerRector;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Php82\Rector\Class_\ReadOnlyClassRector;
use Rector\Php83\Rector\ClassConst\AddTypeToConstRector;
use Rector\PHPUnit\Set\PHPUnitSetList as PHPUnitSetList;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/Api',
        __DIR__ . '/Block',
        __DIR__ . '/Controller',
        __DIR__ . '/Cron',
        __DIR__ . '/DataProvider',
        __DIR__ . '/Model',
        __DIR__ . '/Setup',
        __DIR__ . '/Ui',
        __DIR__ . '/view',
    ])
    ->withPhpVersion(PhpVersion::PHP_84)
    ->withSets([
        SetList::PHP_80,
        SetList::PHP_81,
        SetList::PHP_82,
        SetList::PHP_83,
        SetList::PHP_84,
    ])
    ->withPHPStanConfigs(phpstanConfigs: [__DIR__ . '/phpstan.neon'])
    ->withSets([
        PHPUnitSetList::PHPUNIT_100
    ])
    ->withSkip([
        // Skip specific rules if needed
        ReadOnlyPropertyRector::class,
        ReadOnlyClassRector::class,
        AddTypeToConstRector::class,
        ClassPropertyAssignToConstructorPromotionRector::class,
        NewInInitializerRector::class
    ]);
