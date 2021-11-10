<?php

$finder = PhpCsFixer\Finder::create()
            ->in(__DIR__.'/examples')
            ->in(__DIR__.'/src')
            ->in(__DIR__.'/tests');

return PhpCsFixer\Config::create()
            ->setRiskyAllowed(false)
            ->setRules([
                '@Symfony' => true,
                'binary_operator_spaces' => ['align_double_arrow' => false, 'align_equals' => false],
                'native_function_invocation' => ['include' => ['@compiler_optimized'], 'scope' => 'namespaced', 'strict' => true],
                'no_empty_comment' => false,
                'no_extra_consecutive_blank_lines' => false,
                'not_operator_with_successor_space' => true,
                'ordered_imports' => ['sortAlgorithm' => 'length'],
                'phpdoc_align' => false,
                'phpdoc_no_empty_return' => false,
                'phpdoc_order' => true,
                'php_unit_method_casing' => false,
                'pre_increment' => false,
                'single_trait_insert_per_statement' => false,
                'yoda_style' => false,
            ])
            ->setFinder($finder);
