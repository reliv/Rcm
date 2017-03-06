<?php

namespace Rcm\Page\PageData;

use Interop\Container\ContainerInterface;
use Rcm\Page\PageStatus\PageStatus;

/**
 * @GammaRelease
 * Class PageDataServiceFactory
 *
 * @author    James Jervis
 * @license   License.txt
 * @link      https://github.com/jerv13
 */
class PageDataServiceFactory
{
    /**
     * __invoke
     *
     * @param ContainerInterface $container
     *
     * @return PageDataService
     */
    public function __invoke($container)
    {
        return new PageDataService(
            $container->get('Doctrine\ORM\EntityManager'),
            $container->get('Rcm\Acl\CmsPermissionsChecks'),
            $container->get(PageStatus::class)
        );
    }
}