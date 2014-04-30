<?php

/**
 * Module Config For ZF2
 *
 * PHP version 5.3
 *
 * LICENSE: No License yet
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */

namespace Rcm;
use Zend\Mvc\MvcEvent;
use Zend\Console\Request as ConsoleRequest;

/**
 * ZF2 Module Config.  Required by ZF2
 *
 * ZF2 requires a Module.php file to load up all the Module Dependencies.  This
 * file has been included as part of the ZF2 standards.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
class Module
{

    /**
     * Bootstrap For RCM.
     *
     * @param MvcEvent $event Zend MVC Event
     *
     * @return null
     */
    public function onBootstrap(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();

        $request = $serviceManager->get('request');

        if ($request instanceof ConsoleRequest) {
            return;
        }

        //Add Domain Checker
        $routeListener = $serviceManager->get('Rcm\EventListener\RouteListener');

        $dispatchListener
            = $serviceManager->get('Rcm\EventListener\DispatchListener');

        /** @var \Zend\EventManager\EventManager $eventManager */
        $eventManager = $event->getApplication()->getEventManager();

        // Add Domain Check prior to routing
        $eventManager->attach(
            MvcEvent::EVENT_ROUTE,
            array($routeListener, 'checkDomain'),
            10000
        );

        // Check for redirects from the CMS
        $eventManager->attach(
            MvcEvent::EVENT_ROUTE,
            array($routeListener, 'checkRedirect'),
            9999
        );

        // Set the sites layout.
        $eventManager->attach(
            MvcEvent::EVENT_DISPATCH,
            array($dispatchListener, 'setSiteLayout'),
            10000
        );

        /** @var \Zend\Session\SessionManager $session */
        $session = $serviceManager->get('Rcm\Service\SessionMgr');
        $session->start();
    }

    /**
     * getAutoloaderConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * getConfig() is a requirement for all Modules in ZF2.  This
     * function is included as part of that standard.  See Docs on ZF2 for more
     * information.
     *
     * @return array Returns array to be used by the ZF2 Module Manager
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

}
