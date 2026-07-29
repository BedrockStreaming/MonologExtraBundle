<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__ . '/src')
    ->exclude('vendor')
    ->name('*.php');

return (new Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        '@PHP82Migration' => true,
        '@PHP80Migration:risky' => true,
        'declare_strict_types' => false,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha'
        ],
        'no_unused_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'concat_space' => ['spacing' => 'one'],
        'phpdoc_order' => true,
        'phpdoc_separation' => true,
        'global_namespace_import' => ['import_classes' => false],
        'native_function_invocation' => [
            'include' => [
                '@compiler_optimized'
            ], 'scope' => 'namespaced'
        ],
    ])
    ->setFinder($finder);
