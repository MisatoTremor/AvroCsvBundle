<?php

return PhpCsFixer\Config::create()
    ->setRules(
        [
            '@Symfony' => true,
            'array_syntax' => ['syntax' => 'short'],
            'ordered_imports' => true,
        ]
    )
    ->setUsingCache(true)
    ->setCacheFile(__DIR__.'/.php_cs.cache');
