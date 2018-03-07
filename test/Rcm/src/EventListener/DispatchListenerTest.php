<?php
/**
 * Unit Test for the Dispatch Listener Event
 *
 * This file contains the unit test for Dispatch Listener Event
 *
 * PHP version 5.3
 *
 * LICENSE: BSD
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2018 Reliv International
 * @license   License.txt New BSD License
 * @version   GIT: <git_id>
 * @link      http://github.com/reliv
 */

namespace RcmTest\EventListener;

require_once __DIR__ . '/../../../autoload.php';

use Rcm\Entity\Site;
use Rcm\EventListener\DispatchListener;
use Zend\Mvc\MvcEvent;
use Zend\View\HelperPluginManager;

/**
 * Unit Test for Dispatch Listener Event
 *
 * Unit Test for Dispatch Listener Event
 *
 * @category  Reliv
 * @package   Rcm
 * @author    Westin Shafer <wshafer@relivinc.com>
 * @copyright 2012 Reliv International
 * @license   License.txt New BSD License
 * @version   Release: 1.0
 * @link      http://github.com/reliv
 */
class DispatchListenerTest extends \PHPUnit_Framework_TestCase
{

    /** @var string used for mock */
    protected $title;

    /** @var \stdClass Used for mock */
    protected $mockHeadTitle;

    /**
     * Test Set Site Layout
     *
     * @return void
     *
     * @covers \Rcm\EventListener\DispatchListener
     */
    public function testSetSiteLayout()
    {
        $favicon = 'someFavicon';
        $title = 'My Site Title';
        $layout = 'myLayout';

        $mockLayoutManager = $this->getMockBuilder(\Rcm\Service\LayoutManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockLayoutManager->expects($this->any())
            ->method('getSiteLayout')
            ->will($this->returnValue($layout));

        $currentSite = new Site('user123');
        $currentSite->setSiteId(1);
        $currentSite->setFavIcon($favicon);
        $currentSite->setSiteTitle($title);
        $currentSite->setSiteLayout($layout);

        $testCase = [
            [\Rcm\Service\LayoutManager::class, $mockLayoutManager],
            [\Rcm\Service\CurrentSite::class, $currentSite],
        ];

        // Zend\ServiceManager\ServiceLocatorInterface
        $mockServiceLocator = $this->getMockBuilder('\Zend\ServiceManager\ServiceLocatorInterface')
            ->disableOriginalConstructor()
            ->getMock();

        $mockServiceLocator->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap($testCase)
            );

        $listener = new DispatchListener(
            $mockServiceLocator
        );

        $event = new MvcEvent();

        $listener->setSiteLayout($event);

        $view = $event->getViewModel();

        $template = $view->getTemplate();

        $this->assertEquals('layout/' . $layout, $template);
    }

    /**
     * Used to mock out head title correctly
     *
     * @param string $title Set Title
     *
     * @return null|string
     */
    public function callBackForHeadTitle($title = null)
    {
        if (!empty($title)) {
            $this->title = $title;

            return $this->mockHeadTitle;
        }

        return $this->mockHeadTitle;
    }
}
