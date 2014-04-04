<?php
error_reporting(-1);

// Ensure that composer has installed all dependencies
if (!@require dirname(__DIR__) . '/vendor/autoload.php') {
    die("Dependencies must be installed using composer:\n\nphp composer.phar install\n\n"
        . "See http://getcomposer.org for help with installing composer\n");
}

// Register services with the GuzzleTestCase
Guzzle\Tests\GuzzleTestCase::setMockBasePath(__DIR__ . '/mock');

$serviceBuilder = \Guzzle\Service\Builder\ServiceBuilder::factory(array(
    'services'	=> array(
        'keen-io' => array(
            'class'     => 'KeenIO\Client\KeenIOClient',
            'params'    => array(
                'projectId' => $_SERVER['PROJECT_ID'],
                'masterKey'	=> $_SERVER['MASTER_KEY'],
                'writeKey'	=> $_SERVER['WRITE_KEY'],
                'readKey'	=> $_SERVER['READ_KEY'],
                'version'	=> $_SERVER['API_VERSION']
            )
        )
    )
) );

Guzzle\Tests\GuzzleTestCase::setServiceBuilder( $serviceBuilder );

// Emit deprecation warnings
Guzzle\Common\Version::$emitWarnings = true;
