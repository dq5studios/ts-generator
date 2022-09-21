<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        "@Symfony" => true,
        "concat_space" => ["spacing" => "one"],
        "phpdoc_summary" => false,
        "phpdoc_to_comment" => ["ignored_tags" => ["var"]],
        "single_quote" => false,
        "class_definition" => ["space_before_parenthesis" => true],
    ])
    ->setFinder($finder)
;