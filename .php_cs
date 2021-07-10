<?php


$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
    ->in(__DIR__.'/tests')
;

return PhpCsFixer\Config::create()

    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
    ])
    ->setFinder($finder)
;
