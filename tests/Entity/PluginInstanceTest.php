<?php
namespace Rcm\Entity;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-06 at 10:36:13.
 */
class PluginInstanceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PluginInstance
     */
    protected $PluginInstance;

    protected $pluginInstanceDataSet;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->PluginInstance = new PluginInstance;

        $this->pluginInstanceDataSet = array(
            'instanceId' => 1024,
            'plugin' => 'RcmHtml',
            'siteWide' => true,
            'siteWideName' => 'Test Plugin Name',
            'layoutContainer' => 3,
            'renderOrder' => 5
        );
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Rcm\Entity\PluginInstance::getInstanceId
     * @covers Rcm\Entity\PluginInstance::setInstanceId
     */
    public function testGetAndSetInstanceId()
    {
        $this->PluginInstance->setInstanceId(
            $this->pluginInstanceDataSet['instanceId']
        );

        $this->assertEquals(
            $this->pluginInstanceDataSet['instanceId'],
            $this->PluginInstance->getInstanceId()
        );
    }

    /**
     * @covers Rcm\Entity\PluginInstance::getPlugin
     * @covers Rcm\Entity\PluginInstance::setPlugin
     */
    public function testGetAndSetPlugin()
    {
        $this->PluginInstance->setPlugin(
            $this->pluginInstanceDataSet['plugin']
        );

        $this->assertEquals(
            $this->pluginInstanceDataSet['plugin'],
            $this->PluginInstance->getPlugin()
        );
    }

    /**
     * @covers Rcm\Entity\PluginInstance::isSiteWide
     * @covers Rcm\Entity\PluginInstance::setSiteWide
     */
    public function testIsSiteWideAndSetSiteWide()
    {
        $this->PluginInstance->setSiteWide();
        $this->assertTrue($this->PluginInstance->isSiteWide());
    }

    /**
     * @covers Rcm\Entity\PluginInstance::isSiteWide
     */
    public function testIsNotSiteWide()
    {
        $this->assertFalse($this->PluginInstance->isSiteWide());
    }

    /**
     * @covers Rcm\Entity\PluginInstance::isSiteWide
     * @covers Rcm\Entity\PluginInstance::setSiteWide
     * @covers Rcm\Entity\PluginInstance::setPageOnlyPlugin
     */
    public function testWasSiteWideNoLongerSiteWide()
    {
        $this->PluginInstance->setSiteWide();
        $this->PluginInstance->setPageOnlyPlugin();

        $this->assertFalse($this->PluginInstance->isSiteWide());
    }


}
