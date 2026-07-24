<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
    ])
    ->withParallel()
    ->withPhpSets(php82: true)
    ->withImportNames(importShortClasses: false);