<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
;

$config = new PhpCsFixer\Config();
return $config->setRules([
        "@Symfony" => true,
        "single_quote" => false,
    ])
    ->setFinder($finder)
;