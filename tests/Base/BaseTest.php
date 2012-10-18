<?php
namespace Rcm\Base;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2012-07-05 at 13:19:28.
 */
class BaseTest extends \PHPUnit_Framework_TestCase
{

    /*
     * Example Call
     *
     * $this->getEmMock(
     *           array(
     *               'RcmPluginCommon\Entity\JsonContent'=>array(
     *                   'findOneByInstanceId'=>$contentEntity
     *               )
     *           )
     *       )
     *
     */
    protected function getEmMock($repositories=array())
    {

        $em = $this->getMockEm();

        $repoMocks = array();

        foreach ($repositories as $name => $repo) {

            $repoMocks[$name] = $this->getMock(
                $name,
                array_keys($repo)
            );

            foreach ($repo as $method => $returns) {
                $repoMocks[$name]->expects($this->any())
                    ->method($method)
                    ->will($this->returnValue($returns));
            }
        }

        $em->expects($this->any())
            ->method('getRepository')
            ->with($this->anything())
            ->will(
                $this->returnCallback(
                    function($repo) use ($repoMocks)
                    {
                        return $repoMocks[$repo];
                    }
                )
        );


        return $em;
    }
    /**
     * This will create a service manager mock that you can use to test zf2
     * factory closures
     *
     * @param $factories the zf2 factories array from
     * Module->getServiceConfiguration()
     *
     * @return \Zend\ServiceManager\ServiceManager
     * @throws \Exception
     */
    function getServiceManagerMock($factories)
    {
        if(empty($GLOBALS['em'])){
            throw new \Exception('You need to add
            $GLOBALS[\'em\'] = $this->getEmMock(); to your setUp()
            and add
            unset ($GLOBALS[\'em\']); to your tearDown()');
        }
        $sm = new \Zend\ServiceManager\ServiceManager();
        foreach ($factories as $name => $factory) {
            $sm->setFactory($name, $factory);
        }
        $sm->setFactory(
            'doctrine.entitymanager.ormdefault',
            function()
            {
                return $GLOBALS['em'];
            }
        );
        return $sm;
    }

    private function getMockEm()
    {
        $em = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();

        $em->expects($this->any())
            ->method('getClassMetadata')
            ->will($this->returnValue((object)array('name' => 'aClass')));

        $em->expects($this->any())
            ->method('persist')
            ->will($this->returnValue(null));

        $em->expects($this->any())
            ->method('flush')
            ->will($this->returnValue(null));


        return $em;
    }

    public function getSiteEntity()
    {

    }

    public function testTrueTest(){
        $this->assertTrue(true);
    }
}
