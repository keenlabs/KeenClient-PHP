<?php
/**
 * Keen IO
 *
 * @author Tom Anderson <tom.h.anderson@gmail.com
 * @license MIT
 */

namespace KeenIO;

use Zend\Mvc\ModuleRouteListener,
    Zend\ModuleManager\ModuleManager,
    Zend\EventManager\EventManager,
    Zend\EventManager\StaticEventManager;

class Module {
    public function init(ModuleManager $manager)
    {
        $this->setEventManager($manager->getEventManager());

#        $this->getEventManager()->attach(
#            array('User\Model\User'),
#            'create',
#            array($this, 'addScriptsToUser')
#        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function setEventManager(EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
        return $this;
    }

    public function getEventManager()
    {
        return StaticEventManager::getInstance();
    }
}
