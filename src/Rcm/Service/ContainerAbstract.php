<?php
/**
 * Abstract Class for Containers.
 *
 * This file contains the abstract class used by plugin containers.
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

namespace Rcm\Service;

use Doctrine\ORM\EntityRepository;
use Rcm\Exception\ContainerNotFoundException;
use Rcm\Exception\InvalidArgumentException;
use Rcm\Exception\RuntimeException;
use Zend\Cache\Storage\StorageInterface;

/**
 * Abstract Class for Containers.
 *
 * Abstract Class for Containers.
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      https://github.com/reliv
 */
abstract class ContainerAbstract
{
    /** @var \Rcm\Service\PluginManager */
    protected $pluginManager;

    /** @var \Rcm\Repository\ContainerInterface  */
    protected $repository;

    /** @var \Zend\Cache\Storage\StorageInterface  */
    protected $cache;

    /** @var \Rcm\Service\SiteManager */
    protected $siteManager;

    /** @var array */
    protected $storedContainers;

    /** @var boolean */
    protected $showStaged;

    /**
     * Constructor
     *
     * @param PluginManager    $pluginManager Rcm Plugin Manager
     * @param EntityRepository $repository    Doctrine Entity Manager
     * @param StorageInterface $cache         Zend Cache Manager
     * @param SiteManager      $siteManager   Rcm Site Manager
     */
    public function __construct(
        PluginManager $pluginManager,
        EntityRepository $repository,
        StorageInterface $cache,
        SiteManager $siteManager
    ) {
        $this->pluginManager = $pluginManager;
        $this->repository    = $repository;
        $this->cache         = $cache;
        $this->siteManager        = $siteManager;
    }

    /**
     * Get All the revision info and cache if possible.
     *
     * @param string       $name       Page Name
     * @param null|integer $revision   Revision Id
     * @param null|string  $type       Type of Container.  Currently only used by
     *                                 the page container.
     * @param boolean      $showStaged Show staged version.  Defaults to
     *                                 false.
     * @param null|integer $siteId     Site Id
     *
     * @return null|array
     * @throws \Exception
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getRevisionInfo(
        $name,
        $revision=null,
        $type='n',
        $showStaged = false,
        $siteId = null
    ) {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new InvalidArgumentException('Invalid Site ID');
        }

        if (empty($revision) && $showStaged) {
            $revision = $this->getStagedRevisionId($name, $type, $siteId);
        }

        if (empty($revision)) {
            $revision = $this->getPublishedRevisionId($name, $type, $siteId);
        }

        if (empty($revision)) {
            throw new ContainerNotFoundException(
                'No container found for '.$name
            );
        }

        $cacheKey = get_class($this).'_'.$siteId.'_'.$type.'_'.$name.'_'.$revision;

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $pageInfo = $this->getRevisionDbInfo($name, $revision, $type, $siteId);

        $this->getPluginRenderedInstances($pageInfo['revision']);

        $canCache = $this->canCacheRevision($pageInfo['revision']);

        if ($canCache) {
            $this->cache->setItem($cacheKey, $pageInfo);
        }

        return $pageInfo;
    }

    /**
     * Get Published Container Revision ID
     *
     * @param string       $name   Container Name
     * @param null|string  $type   Type of Container.  Currently only used by the
     *                             page container.
     * @param null|integer $siteId Site Id
     *
     * @return null|integer
     * @throws \Rcm\Exception\PageNotFoundException
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getPublishedRevisionId($name, $type='n', $siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new InvalidArgumentException('Invalid Site ID');
        }

        $cacheKey
            = get_class($this).'_'.$siteId.'_'.$type.'_'.$name.'_currentRevision';

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $result = $this->repository
            ->getPublishedRevisionId($siteId, $name, $type);

        if (!empty($result)) {
            $this->cache->setItem($cacheKey, $result);
        }

        return $result;
    }

    /**
     * Get the Staged Revision Id and cache for later use
     *
     * @param string       $name   Page Name
     * @param null|string  $type   Page Type.  Type "n" is default
     * @param null|integer $siteId Site Id
     *
     * @return null|integer
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getStagedRevisionId($name, $type='n', $siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new InvalidArgumentException('Invalid Site ID');
        }

        $cacheKey
            =  get_class($this).'_'.$siteId.'_'.$type.'_'.$name.'_stagedRevision';

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $result = $this->repository->getStagedRevisionId(
            $siteId,
            $name,
            $type
        );

        $this->cache->setItem($cacheKey, $result);

        return $result;
    }

    /**
     * Get Page Revision DB Info and cache for later
     *
     * @param string       $name       Page Name
     * @param string       $revisionId Revision Id
     * @param string       $type       Type of Container.  Currently only used by
     *                                 the page container.
     * @param null|integer $siteId     Site Id
     *
     * @return null|array Database Result Set
     * @throws \Rcm\Exception\PageNotFoundException
     * @throws \Rcm\Exception\InvalidArgumentException
     */
    public function getRevisionDbInfo($name, $revisionId, $type='n', $siteId=null)
    {
        if (!$siteId) {
            $siteId = $this->siteManager->getCurrentSiteId();
        }

        if (!$this->siteManager->isValidSiteId($siteId)) {
            throw new InvalidArgumentException('Invalid Site ID');
        }

        $storedContainers = $this->storedContainers;

        $cacheKey
            = get_class($this).'_data_'.$siteId.'_'.$type.'_'.$name.'_'.$revisionId;

        if (!empty($storedContainers['data'][$siteId][$type][$name][$revisionId])) {
            return $storedContainers['data'][$siteId][$type][$name][$revisionId];
        }

        if ($this->cache->hasItem($cacheKey)) {
            return $this->cache->getItem($cacheKey);
        }

        $result = $this->repository->getRevisionDbInfo(
            $siteId,
            $name,
            $revisionId,
            $type
        );

        $this->cache->setItem($cacheKey, $result);

        $this->storedContainers['data'][$siteId][$type][$name][$revisionId]
            = $result;

        return $result;
    }

    /**
     * Get a rendered plugin Instance from the Plugin Manager
     *
     * @param array &$revisionData Database result set
     *
     * @return void
     */
    protected function getPluginRenderedInstances(&$revisionData)
    {
        foreach ($revisionData['pluginInstances'] as &$pluginWrapper) {
            $renderedData = $this->pluginManager->getPluginByInstanceId(
                $pluginWrapper['instance']['pluginInstanceId']
            );

            $pluginWrapper['instance']['renderedData'] = $renderedData;
        }
    }

    /**
     * Check all the plugin instances to see if the entire container revision
     * can be cached.
     *
     * @param array &$revisionData Database result set
     *
     * @return bool
     */
    protected function canCacheRevision(&$revisionData)
    {
        $canCache = true;

        foreach ($revisionData['pluginInstances'] as &$pluginWrapper) {
            if (empty($pluginWrapper['instance']['canCache'])) {
                $canCache = false;
            }
        }

        return $canCache;
    }
}