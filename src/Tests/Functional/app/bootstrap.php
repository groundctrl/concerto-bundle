<?php

use Doctrine\Common\Annotations\AnnotationRegistry;

$loader = require __DIR__ . '/../../../../vendor/autoload.php';

AnnotationRegistry::registerLoader([ $loader, 'loadClass' ]);

// intl
/*if (!function_exists('intl_get_error_code')) {
    require_once __DIR__.'/../../vendor/symfony/symfony/src/Symfony/Component/Intl/Resources/stubs/functions.php';
}*/

return $loader;
