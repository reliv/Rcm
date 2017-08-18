<?php

namespace Rcm\Factory;

use Rcm\Service\ResponseHandler;
use Zend\Mvc\ResponseSender\HttpResponseSender;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Container Manager
 *
 * Factory for the Container Manager.
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 *
 */
class ResponseHandlerFactory
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return ResponseHandler
     */
    public function __invoke($serviceLocator)
    {
        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get(\Rcm\Service\CurrentSite::class);

        /** @var \RcmUser\Service\RcmUserService $rcmUserService */
        $rcmUserService = $serviceLocator->get(\RcmUser\Service\RcmUserService::class);

        /** @var \Zend\Stdlib\Request $request */
        $request = $serviceLocator->get('request');

        $responseSender = new HttpResponseSender();

        return new ResponseHandler(
            $request,
            $currentSite,
            $responseSender,
            $rcmUserService
        );
    }
}
