<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;
use Rector\ValueObject\PhpVersion;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/src',
    ])
    ->withSkip([
        __DIR__ . '/vendor',
    ])
    ->withPhpVersion(PhpVersion::PHP_82)
    ->withComposerBased(
        phpunit: true,
        symfony: true,
    )
    ->withSets([
        SetList::PHP_82,
    ]);
