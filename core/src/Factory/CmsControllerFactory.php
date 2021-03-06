<?php

namespace Rcm\Factory;

use Rcm\Controller\CmsController;
use Rcm\Page\Renderer\PageRendererBc;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the CmsController
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
class CmsControllerFactory
{
    /**
     * Create Service
     *
     * @param ServiceLocatorInterface $controllerManager Zend Controller Manager
     *
     * @return CmsController
     */
    public function __invoke($controllerManager)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $controllerMgr For IDE */
        $controllerMgr = $controllerManager;

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerMgr->getServiceLocator();

        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager = $serviceLocator->get(\Rcm\Service\LayoutManager::class);

        /** @var \Rcm\Entity\Site $currentSite */
        $currentSite = $serviceLocator->get(\Rcm\Service\CurrentSite::class);

        /** @var \Doctrine\ORM\EntityManagerInterface $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');

        /** @var \Rcm\Repository\Page $pageRepo */
        $pageRepo = $entityManager->getRepository(\Rcm\Entity\Page::class);

        return new CmsController(
            $serviceLocator->get(PageRendererBc::class),
            $currentSite
        );
    }
}
