<?php

namespace KeenIO;

return array(
    'di' => array(
        'instance' => array(
            'alias' => array(
                'serviceKeenIO' => 'KeenIO\Service\KeenIO',
            ),

            'serviceKeenIO' => array(
                'parameters' => array(
                    'serviceManager' => 'Zend\ServiceManager\ServiceManager',
                ),
            ),
        ),
    ),
);