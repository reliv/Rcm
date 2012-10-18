<?php
namespace Rcm\View\Helper;

use Zend\View\Helper\RenderChildModel,
Zend\View\Model\ViewModel,
Zend\View\Renderer\PhpRenderer,
Zend\View\Resolver\TemplateMapResolver;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-08 at 11:34:53.
 */
class AddLayoutContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var AddLayoutContainer
     */
    protected $object;

    protected $resolver;

    protected $renderer;

    protected $helper;

    /** @var \Zend\View\Helper\ViewModel */
    protected $viewModelHelper;

    /** @var \Zend\View\Model\ViewModel */
    protected $parent;

    protected $children;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new AddLayoutContainer;

        $templateDIr = __DIR__ . '/../TestTemplates/';

        $this->resolver = new TemplateMapResolver(array(
            'layout'  => $templateDIr .'add-layout-container-page-layout.phtml',
            'child1'  => $templateDIr .'add-layout-container-child-one.phtml',
            'child2'  => $templateDIr .'add-layout-container-child-two.phtml',
        ));

        $this->renderer = $renderer = new PhpRenderer();
        $renderer->setCanRenderTrees(true);
        $renderer->setResolver($this->resolver);

        $this->viewModelHelper = $renderer->plugin('view_model');
        $this->helper          = $renderer->plugin(
            'Rcm\View\Helper\AddLayoutContainer'
        );

        $this->parent = new ViewModel();
        $this->parent->setTemplate('layout');
        $this->viewModelHelper->setRoot($this->parent);
        $this->viewModelHelper->setCurrent($this->parent);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Rcm\View\Helper\AddLayoutContainer::renderLayoutContainer
     * @covers Rcm\View\Helper\AddLayoutContainer::__invoke
     */
    public function test__invoke()
    {
        $this->setupContainers();

        $returnedHtml = $this->helper->__invoke(2);

        $this->assertContains(
            'id="rcmContainer_2"',
            $returnedHtml
        );

        $this->assertContains(
            'id="child1',
            $returnedHtml
        );

        $this->assertContains(
            'id="child2',
            $returnedHtml
        );
    }

    /**
     * @covers Rcm\View\Helper\AddLayoutContainer::renderLayoutContainer
     */
    public function testRenderLayoutContainer()
    {
        $this->setupContainers();
        $returnedHtml = $this->helper->renderLayoutContainer(2);

        $this->assertContains(
            'id="rcmContainer_2"',
            $returnedHtml
        );

        $this->assertContains(
            'id="child1',
            $returnedHtml
        );

        $this->assertContains(
            'id="child2',
            $returnedHtml
        );

    }

    private function setupContainers()
    {
        $pluginOne = new \Rcm\Entity\PluginInstance();
        $pluginOne->setInstanceId(32);
        $pluginOne->setPlugin('RcmHtml');

        $viewOne = new ViewModel();
        $viewOne->setTemplate('child1');
        $pluginOne->setViewModel($viewOne);


        $pluginTwo = new \Rcm\Entity\PluginInstance();
        $pluginTwo->setInstanceId(33);
        $pluginTwo->setPlugin('RcmHtml2');

        $viewTwo = new ViewModel();
        $viewTwo->setTemplate('child2');
        $pluginTwo->setViewModel($viewTwo);

        $plugins[2][0] = $pluginOne;
        $plugins[2][1] = $pluginTwo;

        $this->parent->plugins = $plugins;
    }
}
