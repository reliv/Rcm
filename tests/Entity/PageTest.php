<?php
namespace Rcm\Entity;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-06 at 07:21:30.
 */
class PageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Page
     */
    protected $Page;

    /**
     * @var array
     */
    protected $pageDataSet;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->Page = new Page;

        $this->pageDataSet = array(
            'pageId' => 42,
            'name'   => 'SomePage',
            'author' => 4057510001,
            'createdDate' => new \DateTime('2011-07-08 11:14:15'),
            'lastPublished' => new \DateTime('2012-07-08 11:14:15'),
            'currentRevision' => new \Rcm\Entity\PageRevision(),
            'sites' => array(
                new \Rcm\Entity\Site(),
            ),
            'revisions' => array(
                new \Rcm\Entity\PageRevision(),
            ),
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
     * Tests the constructor
     *
     * @covers Rcm\Entity\Page::__construct
     * @covers Rcm\Entity\Page::getRawSites
     * @covers Rcm\Entity\Page::getRawRevisions
     */
    public function testConstructor()
    {
        $this->assertInstanceOf(
            '\Doctrine\Common\Collections\ArrayCollection',
            $this->Page->getRawSites()
        );

        $this->assertInstanceOf(
            '\Doctrine\Common\Collections\ArrayCollection',
            $this->Page->getRawRevisions()
        );
    }

    /**
     * @covers Rcm\Entity\Page::getPageId
     * @covers Rcm\Entity\Page::setPageId
     */
    public function testGetAndSetPageId()
    {
        $this->Page->setPageId($this->pageDataSet['pageId']);
        $this->assertEquals(
            $this->pageDataSet['pageId'],
            $this->Page->getPageId()
        );
    }

    /**
     * @covers Rcm\Entity\Page::getName
     * @covers Rcm\Entity\Page::setName
     */
    public function testGetAndSetName()
    {
        $this->Page->setName($this->pageDataSet['name']);
        $this->assertEquals(
            $this->pageDataSet['name'],
            $this->Page->getName()
        );
    }

    /**
     * @covers Rcm\Entity\Page::setName
     * @covers Rcm\Exception\InvalidArgumentException
     *
     * @expectedException \Rcm\Exception\InvalidArgumentException
     */
    public function testSetNameWithSpaces()
    {
        $this->Page->setName('This Page Name Contains Spaces');
    }

    /**
     * @covers Rcm\Entity\Page::getAuthor
     * @covers Rcm\Entity\Page::setAuthor
     */
    public function testGetAndSetAuthor()
    {
        $this->Page->setAuthor($this->pageDataSet['author']);
        $this->assertEquals(
            $this->pageDataSet['author'],
            $this->Page->getAuthor()
        );
    }

    /**
     * @covers Rcm\Entity\Page::getCreatedDate
     * @covers Rcm\Entity\Page::setCreatedDate
     */
    public function testGetAndSetCreatedDate()
    {
        $this->Page->setCreatedDate($this->pageDataSet['createdDate']);
        $this->assertEquals(
            $this->pageDataSet['createdDate'],
            $this->Page->getCreatedDate()
        );
    }

    /**
     * @covers Rcm\Entity\Page::getLastPublished
     * @covers Rcm\Entity\Page::setLastPublished
     */
    public function testGetAndSetLastPublished()
    {
        $this->Page->setLastPublished($this->pageDataSet['lastPublished']);
        $this->assertEquals(
            $this->pageDataSet['lastPublished'],
            $this->Page->getLastPublished()
        );
    }

    /**
     * @covers Rcm\Entity\Page::getPublishedRevision
     * @covers Rcm\Entity\Page::setPublishedRevision
     */
    public function testGetAndSetPublishedRevision()
    {
        $this->Page->setPublishedRevision(
            $this->pageDataSet['currentRevision']
        );

        $this->assertEquals(
            $this->pageDataSet['currentRevision'],
            $this->Page->getPublishedRevision()
        );
    }

    /**
     * @covers Rcm\Entity\Page::getCurrentRevision
     * @covers Rcm\Entity\Page::setCurrentRevision
     */
    public function testGetAndSetCurrentRevision()
    {
        $this->Page->setCurrentRevision(
            $this->pageDataSet['currentRevision']
        );

        $this->assertEquals(
            $this->pageDataSet['currentRevision'],
            $this->Page->getCurrentRevision()
        );
    }

    /**
     * @covers Rcm\Entity\Page::getSites
     * @covers Rcm\Entity\Page::setSite
     */
    public function testGetAndSetSites()
    {
        foreach ($this->pageDataSet['sites'] as $site) {
            $this->Page->setSite($site);
        }

        $this->assertEquals(
            $this->pageDataSet['sites'],
            $this->Page->getSites()
        );
    }

    /**
     * @covers Rcm\Entity\Page::getRevisions
     * @covers Rcm\Entity\Page::addPageRevision
     */
    public function testGetAndAddRevisions()
    {
        foreach ($this->pageDataSet['revisions'] as $revision) {
            $this->Page->addPageRevision($revision);
        }

        $this->assertEquals(
            $this->pageDataSet['revisions'],
            $this->Page->getRevisions()
        );
    }

    /**
     * @covers Rcm\Entity\Page::toArray
     */
    public function testToArray()
    {
        $this->Page->setPageId($this->pageDataSet['pageId']);
        $this->Page->setName($this->pageDataSet['name']);
        $this->Page->setAuthor($this->pageDataSet['author']);
        $this->Page->setCreatedDate($this->pageDataSet['createdDate']);
        $this->Page->setLastPublished($this->pageDataSet['lastPublished']);
        $this->Page->setPublishedRevision(
            $this->pageDataSet['currentRevision']
        );
        $this->Page->setCurrentRevision(
            $this->pageDataSet['currentRevision']
        );

        foreach ($this->pageDataSet['sites'] as $site) {
            $this->Page->setSite($site);
        }

        foreach ($this->pageDataSet['revisions'] as $revision) {
            $this->Page->addPageRevision($revision);
        }

        $this->assertEquals(
            $this->pageDataSet,
            $this->Page->toArray()
        );
    }
}
