<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
//    ->in(__DIR__ . '/tests')
;

$config = new PhpCsFixer\Config();

return $config->setRiskyAllowed(true)
    ->registerCustomFixers(new PhpCsFixerCustomFixers\Fixers())
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        '@PhpCsFixer' => true,
        'strict_param' => true,
        'single_line_empty_body' => false,
        'concat_space' => [
            'spacing' => 'one'
        ],
        'function_declaration' => [
            'closure_fn_spacing' => 'none'
        ],
        'cast_spaces' => [
            'space' => 'none'
        ],
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed' => false,
            'allow_unused_params' => false,
        ],
        'phpdoc_to_comment' => [
            'ignored_tags' => [
                'var'
            ]
        ],
        'global_namespace_import' => [
            'import_classes' => true
        ],
        'php_unit_test_class_requires_covers' => false,
        'php_unit_internal_class' => false,
        PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\NoDuplicatedImportsFixer::name() => true,
        PhpCsFixerCustomFixers\Fixer\NoPhpStormGeneratedCommentFixer::name() => true,
    ])
    ->setFinder($finder);
