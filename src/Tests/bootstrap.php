<?php

use Doctrine\Common\Annotations\AnnotationRegistry;


#@TODO: FIX PATHS *will be fixed when it's its own bundle
#@todo: development env only tests
$loader = require __DIR__.'/../../vendor/autoload.php';

$loader->add('Ctrl\Bundle\ConcertoBundle\Tests\Fixtures', __DIR__ . '/Fixtures/');

// intl

if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../../vendor/symfony/symfony/src/Symfony/Component/Intl/Resources/stubs/functions.php';
}


AnnotationRegistry::registerLoader([ $loader, 'loadClass' ]);
