<?php
error_reporting(-1);

// Ensure that composer has installed all dependencies
if (!@require dirname(__DIR__) . '/vendor/autoload.php') {
    die("Dependencies must be installed using composer:\n\nphp composer.phar install\n\n"
        . "See http://getcomposer.org for help with installing composer\n");
}
