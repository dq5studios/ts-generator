<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude("cache/")
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        "@PHP80Migration" => true,
        "@PHP80Migration:risky" => true,
        "@Symfony" => true,
        "@Symfony:risky" => true,
        "class_definition" => ["space_before_parenthesis" => true],
        "concat_space" => ["spacing" => "one"],
        "global_namespace_import" => ["import_classes" => true, "import_constants" => true, "import_functions" => true],
        "ordered_imports" => ["imports_order" => ["class", "const", "function"]],
        "phpdoc_summary" => false,
        "phpdoc_to_comment" => ["ignored_tags" => ["var", "psalm-suppress"]],
        "single_quote" => false,
        "types_spaces" => ["space_multiple_catch" => "single"],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
