<?php
namespace Rcm\Model;

require_once __DIR__.'/../Base/BaseSite.php';

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-08 at 10:11:25.
 */
class PageFactoryTest extends \Rcm\Base\BaseSite
{
    /**
     * @var PageFactory
     */
    protected $PageFactory;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->PageFactory = new PageFactory;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Rcm\Model\PageFactory::createPage
     */
    public function testCreatePage()
    {
        $this->PageFactory
            ->setEm($this->getEmMock());

        $site = $this->getSiteEntityForTests();
        $pages = $site->getPages();

        /** @var \Rcm\Entity\Page $pageToUse  */
        $pageToUse = $pages['myPageOne'];

        $pageReturned = $this->PageFactory->createPage(
            $pageToUse->getName(),
            $pageToUse->getAuthor(),
            $pageToUse->getCurrentRevision()->getPageTitle(),
            $pageToUse->getCurrentRevision()->getDescription(),
            $pageToUse->getCurrentRevision()->getKeywords(),
            $pageToUse->getCurrentRevision()->getPageLayout(),
            $site,
            $pageToUse->getCurrentRevision()->getPluginInstances()
        );

        $this->assertInstanceOf(
            '\Rcm\Entity\Page',
            $pageReturned
        );

        $this->assertEquals(
            $pageToUse->getName(),
            $pageReturned->getName()
        );

        $this->assertEquals(
            $pageToUse->getAuthor(),
            $pageReturned->getAuthor()
        );

        $this->assertEquals(
            $pageToUse->getCurrentRevision()->getPageTitle(),
            $pageReturned->getCurrentRevision()->getPageTitle()
        );

        $this->assertEquals(
            $pageToUse->getCurrentRevision()->getDescription(),
            $pageReturned->getCurrentRevision()->getDescription()
        );

        $this->assertEquals(
            $pageToUse->getCurrentRevision()->getKeywords(),
            $pageReturned->getCurrentRevision()->getKeywords()
        );

        $this->assertEquals(
            $pageToUse->getCurrentRevision()->getPageLayout(),
            $pageReturned->getCurrentRevision()->getPageLayout()
        );

        $this->assertEquals(
            $pageToUse->getCurrentRevision()->getPluginInstances(),
            $pageReturned->getCurrentRevision()->getPluginInstances()
        );

        $sitesReturned = $pageReturned->getSites();
        $siteToCheck = $sitesReturned[0];

        $this->assertEquals(
            $site->getSiteId(),
            $siteToCheck->getSiteId()
        );

    }

    /**
     * @covers Rcm\Model\PageFactory::getEm
     * @covers Rcm\Model\PageFactory::setEm
     * @covers Rcm\Model\FactoryAbstract::getEm
     * @covers Rcm\Model\FactoryAbstract::setEm
     */
    public function testGetAndSetEntityManager()
    {
        $this->PageFactory->setEm($this->getEmMock());

        $this->assertInstanceOf(
            '\Doctrine\ORM\EntityManager',
            $this->PageFactory->getEm()
        );
    }

}
