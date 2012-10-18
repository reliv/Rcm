<?php
namespace Rcm;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-06 at 21:57:35.
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Module
     */
    protected $Module;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->Module = new Module;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Rcm\Module::getAutoloaderConfig
     */
    public function testGetAutoloaderConfig()
    {
        $autoloadConfig = $this->Module->getAutoloaderConfig();

        $this->assertArrayHasKey(
            'Zend\Loader\ClassMapAutoloader',
            $autoloadConfig
        );

        $this->assertArrayHasKey(
            'Zend\Loader\StandardAutoloader',
            $autoloadConfig
        );
    }

    /**
     * @covers Rcm\Module::getConfig
     */
    public function testGetConfig()
    {
        $config = $this->Module->getConfig();

        $this->assertArrayHasKey(
            'view_manager',
            $config
        );

        $this->assertArrayHasKey(
            'template_path_stack',
            $config['view_manager']
        );

        foreach ($config['view_manager']['template_path_stack'] as $path)
        {
            $this->assertFileExists($path);
        }

        $this->assertArrayHasKey(
            'addLayoutContainer',
            $config['view_helpers']['invokables']
        );

        $this->assertArrayHasKey(
            'doctrine',
            $config
        );
    }

    /**
     * @covers Rcm\Module::getServiceConfig
     */
    public function testGetServiceConfig()
    {
        $config = $this->Module->getServiceConfig();

        $this->assertTrue(is_array($config));
    }

    /**
     * @covers Rcm\Module::init
     */
    public function testInit()
    {
        $moduleManager = new \Zend\ModuleManager\ModuleManager(array());
        $this->Module->init($moduleManager);

        $shared = $moduleManager->getEventManager()->getSharedManager()->getListeners(
            'Rcm',
            'dispatch'
        );

        $extract = $shared->extract();

        $this->assertAttributeContains(
            'baseControllerInit',
            'callback',
            $extract
        );
    }

    /**
     * @covers Rcm\Module::baseControllerInit
     */
    public function testBaseControllerInitWillCallBaseControllerInit()
    {

        $mockBaseController = $this->getMock(
            __NAMESPACE__.'\Controller\BaseController',
            array('init')
        );

        $mockBaseController->expects($this->once())
            ->method('init');

        $stub = $this->getMock('\Zend\Mvc\MvcEvent');
        $stub->expects($this->any())
            ->method('getTarget')
            ->will($this->returnValue($mockBaseController));

        $this->Module->baseControllerInit($stub);

    }

    /**
     * @covers Rcm\Module::baseControllerInit
     */
    public function testBaseControllerInitSkipsInitWhenNotNeeded()
    {
        $mockBaseController = $this->getMock(
            __NAMESPACE__.'\Controller\BaseController',
            array('init')
        );

        $mockBaseController->expects($this->never())
            ->method('init');

        $stubController = $this->getMock(
            '\Zend\Mvc\Controller\ActionController'
        );

        $stub = $this->getMock('\Zend\Mvc\MvcEvent');
        $stub->expects($this->any())
            ->method('getTarget')
            ->will($this->returnValue($stubController));



        $this->Module->baseControllerInit($stub);
    }
}
