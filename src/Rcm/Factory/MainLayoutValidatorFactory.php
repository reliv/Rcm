<?php
/**
 * Service Factory for the Rcm Main Layout Validator
 *
 * This file contains the factory needed to validate a Rcm Main Layout.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2014 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      https://github.com/reliv
 */
namespace Rcm\Factory;
use Rcm\Validator\MainLayout;
use Rcm\Validator\Page;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Service Factory for the Rcm Main Layout Validator
 *
 * Factory for Rcm Main Layout.
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

class MainLayoutValidatorFactory implements FactoryInterface
{
    /**
     * Creates Service
     *
     * @param ServiceLocatorInterface $serviceLocator Zend Service Locator
     *
     * @return Page
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Rcm\Service\LayoutManager $layoutManager */
        $layoutManager = $serviceLocator->get('\Rcm\Service\LayoutManager');

        return $layoutManager->getMainLayoutValidator();
    }
}