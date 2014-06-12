<?php
/**
 * Unit Test for the IndexController
 *
 * This file contains the unit test for the IndexController
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
 * @link      http://github.com/reliv
 */
namespace RcmTest\Acl;

use Rcm\Acl\ResourceProvider;
use Rcm\Entity\Domain;
use Rcm\Entity\Page;
use Rcm\Entity\Site;

require_once __DIR__ . '/../../../autoload.php';

/**
 * Unit Test for the IndexController
 *
 * Unit Test for the IndexController
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class ResourceProviderTest extends \PHPUnit_Framework_TestCase
{
    protected $config;

    /**
     * Setup config for tests
     *
     * @return void
     */
    public function setup()
    {
        $this->config = $this->getConfig();
    }

    /**
     * Get Main Config Array
     *
     * @return array
     */
    protected function getConfig()
    {
        return array(
            'Sites' => array(
                'resourceId' => 'Sites',
                'parentResourceId' => null,
                'privileges' => array(
                    'read',
                    'edit',
                    'create',
                    'delete',
                    'theme',
                    'admin',
                ),
                'name' => 'Sites',
                'description' => 'Global resource for sites',
            ),

            'Pages' => array(
                'resourceId' => 'Pages',
                'parentResourceId' => null,
                'privileges' => array(
                    'read',
                    'edit',
                    'create',
                    'delete',
                    'copy',
                    'approve',
                    'layout',
                ),
                'name' => 'Pages',
                'description' => 'Global resource for pages',
            ),
        );
    }

    /**
     * Get resource provider with mocks
     *
     * @param bool $skipSite Skip Site Mock setup
     *
     * @return ResourceProvider
     */
    protected function getProviderWithMocks($skipSite=false)
    {
        $mockReturn = array();

        if (!$skipSite) {
            $domain = new Domain();
            $domain->setDomainName('test.reliv.com');

            $page = new Page();
            $page->setName('Test-Page');

            $site = new Site();
            $site->setDomain($domain);
            $site->setSiteId(10);
            $site->addPage($page);

            $mockReturn[] = $site;
        }

        $mockSiteManager = $this->getMockBuilder('\Rcm\Service\SiteManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockSiteManager->expects($this->any())
            ->method('getAllActiveSites')
            ->will($this->returnValue($mockReturn));

        $mockPageManager = $this->getMockBuilder('\Rcm\Service\PageManager')
            ->disableOriginalConstructor()
            ->getMock();

        $mockPluginManager = $this->getMockBuilder('\Rcm\Service\PluginManager')
            ->disableOriginalConstructor()
            ->getMock();

        /** @var \Rcm\Service\SiteManager   $mockSiteManager */
        /** @var \Rcm\Service\PageManager   $mockPageManager */
        /** @var \Rcm\Service\PluginManager $mockPluginManager */
        return new ResourceProvider(
            $this->config,
            $mockSiteManager,
            $mockPageManager,
            $mockPluginManager
        );
    }

    /**
     * Test Get Resources Method
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResources
     * @covers \Rcm\Acl\ResourceProvider::__construct
     */
    public function testGetResourcesNoSites()
    {
        $resourceProvider = $this->getProviderWithMocks(true);

        $return = $resourceProvider->getResources();

        $this->assertEquals($this->config, $return);
    }

    /**
     * Test Get Resources Method No Config
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResources
     */
    public function testGetResourcesNoConfig()
    {
        $this->config = array();

        $resourceProvider = $this->getProviderWithMocks(true);

        $return = $resourceProvider->getResources();

        $this->assertEquals($this->config, $return);
    }

    /**
     * Test Get Resources Method With Sites And Pages
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResources
     * @covers \Rcm\Acl\ResourceProvider::getSiteResources
     * @covers \Rcm\Acl\ResourceProvider::getPageResources
     */
    public function testGetResourcesWithSites()
    {
        $siteExpected = array(
            'Sites.10.Pages.Test-Page' => array (
                'resourceId' => 'Sites.10.Pages.Test-Page',
                'parentResourceId' => 'Sites.10.Pages',
                'privileges' => array (
                    'read',
                    'edit',
                    'create',
                    'delete',
                    'copy',
                    'approve',
                    'layout',
                ),

                'name' => 'test.reliv.com - Pages - Test-Page',
                'description' => 'Global resource for pages',
            ),

            'Sites.10' => array (
                'resourceId' => 'Sites.10',
                'parentResourceId' => 'Sites',
                'privileges' => array (
                    'read',
                    'edit',
                    'create',
                    'delete',
                    'theme',
                    'admin',
                ),

                'name' => 'test.reliv.com',
                'description' => 'Global resource for sites',
            ),

            'Sites.10.Pages' => array (
                'resourceId' => 'Sites.10.Pages',
                'parentResourceId' => 'Sites.10',
                'privileges' => array (
                    'read',
                    'edit',
                    'create',
                    'delete',
                    'copy',
                    'approve',
                    'layout',
                ),

                'name' => 'test.reliv.com - Pages',
                'description' => 'Global resource for pages',
            ),
        );

        $expected = array_merge($siteExpected, $this->config);

        $resourceProvider = $this->getProviderWithMocks();

        $return = $resourceProvider->getResources();

        $this->assertEquals($expected, $return);
    }

    /**
     * Test Get Resource with resource defined in config
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResource
     */
    public function testGetResourceInConfig()
    {
        $resourceProvider = $this->getProviderWithMocks(true);

        $sitesResource = $resourceProvider->getResource('Sites');
        $pagesResource = $resourceProvider->getResource('Pages');

        $this->assertEquals($this->config['Sites'], $sitesResource);
        $this->assertEquals($this->config['Pages'], $pagesResource);
    }

    /**
     * Test Get Resource using dynamic site
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResource
     * @covers \Rcm\Acl\ResourceProvider::dynamicResourceMapper
     * @covers \Rcm\Acl\ResourceProvider::siteResourceMapper
     */
    public function testGetResourceForSite()
    {
        $expected = array (
            'resourceId' => 'Sites.10',
            'parentResourceId' => 'Sites',
            'privileges' => array (
                'read',
                'edit',
                'create',
                'delete',
                'theme',
                'admin',
            ),

            'name' => 'Sites',
            'description' => 'Global resource for sites',
        );

        $resourceProvider = $this->getProviderWithMocks();

        $siteResource = $resourceProvider->getResource('Sites.10');

        $this->assertEquals($expected, $siteResource);
    }

    /**
     * Test Get Resource using dynamic site
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResource
     * @covers \Rcm\Acl\ResourceProvider::dynamicResourceMapper
     * @covers \Rcm\Acl\ResourceProvider::pageResourceMapper
     */
    public function testGetResourceForPage()
    {
        $expected = array (
            'resourceId' => 'Sites.10.Pages.Test-Page',
            'parentResourceId' => 'Sites.10.Pages',
            'privileges' => array (
                'read',
                'edit',
                'create',
                'delete',
                'copy',
                'approve',
                'layout',
            ),

            'name' => 'Pages',
            'description' => 'Global resource for pages',
        );

        $resourceProvider = $this->getProviderWithMocks();

        $siteResource = $resourceProvider->getResource('Sites.10.Pages.Test-Page');

        $this->assertEquals($expected, $siteResource);
    }

    /**
     * Test Get Resource Resource Not Found
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::getResource
     * @covers \Rcm\Acl\ResourceProvider::dynamicResourceMapper
     * @covers \Rcm\Acl\ResourceProvider::siteResourceMapper
     * @covers \Rcm\Acl\ResourceProvider::pageResourceMapper
     */
    public function testGetResourceNoMatch()
    {
        $resourceProvider = $this->getProviderWithMocks();

        $siteResource = $resourceProvider->getResource('Does.Not.Match');

        $this->assertNull($siteResource);
    }

    /**
     * Test Set Provider Id
     *
     * @return void
     * @covers \Rcm\Acl\ResourceProvider::setProviderId
     */
    public function testSetProviderId()
    {
        $expected = '\Rcm\Acl\ResourceProvider';

        $resourceProvider = $this->getProviderWithMocks();

        $reflectedClass = new \ReflectionClass(
            get_class($resourceProvider)
        );

        $property = $reflectedClass->getProperty('providerId');
        $property->setAccessible(true);

        $resourceProvider->setProviderId('Does.Not.Match');

        $providerId = $property->getValue($resourceProvider);

        $this->assertEquals($expected, $providerId);
    }
}
