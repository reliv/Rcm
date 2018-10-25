<?php

namespace Rcm\ImmutableHistory;

class Repository
{
    protected $entityClassName;
    protected $locatorColumnNames;
    protected $entityManger;

    /**
     * @TODO allow per-resource-type locator validators to be injected and use them
     * @TODO set dates, user, and reason
     * @TODO replace all \Rcm\ImmutableHistory\Entity\ImmutablePageVersion with interface in here
     * @TODO make userId and programmatic and any other fields that should be required, required
     * @TODO get statuses from constants? And actions too?
     *
     * Repository constructor.
     * @param $entityClassName
     * @param $locatorColumnNames
     * @param $entityManger
     */
    public function __construct($entityClassName, $locatorColumnNames, \Doctrine\ORM\EntityManager $entityManger)
    {
        $this->entityClassName = $entityClassName;
        $this->locatorColumnNames = $locatorColumnNames;
        $this->entityManger = $entityManger;
    }

    public function createUnpublishedFromNothing(array $locator, $content, $userId, $programmaticReason)
    {
        $newVersion = new ($this->entityClassName)(
            '?? @TODO ??', //@TODO
            null,
            new \DateTime(),
            VersionStatuses::UNPUBLISHED,
            'createUnpublishedFromNothing',
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function publishFromNothing(array $locator, $content, $userId, $programmaticReason)
    {
        $resourceId = '??@TODO!??'; //@TODO!

        $newVersion = new ($this->entityClassName)(
            '?? @TODO ??', //@TODO
            null,
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            'publishFromNothing',
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function publishFromExistingVersion($versionId, $userId, $programmaticReason)
    {
//        $newVersion = $this->entityManger->find($this->entityClassName, $versionId);
// @TODO how can this work in RCM?
    }

    public function depublish(array $locator, $userId, $programmaticReason)
    {
        $newVersion = new ($this->entityClassName)(
            '?? @TODO ??', //@TODO
            null,
            new \DateTime(),
            VersionStatuses::DEPUBLISHED,
            'depublish',
            $userId,
            $programmaticReason,
            $locator,
            $content
        );

        $this->entityManger->persist($newVersion);
        $this->entityManger->flush($newVersion);
    }

    public function relocate(array $oldLocator, array $newLocator)
    {
        $originalEntity = $this->getByLocator($oldLocator); //@TODO handle not found case

        $relocateDepublishVersion = new ($this->entityClassName)(
            '?? @TODO ??', //@TODO
            null,
            new \DateTime(),
            VersionStatuses::DEPUBLISHED,
            'relocateDepublish',
            $userId,
            $programmaticReason,
            $originalEntity->getLocator(),
            $originalEntity->getContent()
        );

        $this->entityManger->persist($relocateDepublishVersion);

        $relocatePublishVersion = new ($this->entityClassName)(
            '?? @TODO ??', //@TODO
            null,
            new \DateTime(),
            VersionStatuses::PUBLISHED,
            'relocatePublish',
            $userId,
            $programmaticReason,
            $newLocator,
            $originalEntity->getContent()
        );

        $this->entityManger->persist($relocatePublishVersion);

        $this->entityManger->flush([$relocateDepublishVersion, $relocatePublishVersion]);
    }

    public function copy(array $relocateDepublishVersion, array $relocatePublishVersion)
    {
        $originalEntity = $this->getByLocator($oldLocator); //@TODO handle not found case
    }

    public function getOneByLocator(array $locator)
    {
        $doctrineRepo = $this->entityManger->getRepository($this->entityClassName);
        return $doctrineRepo->findOneBy($locator); //@TODO handle not found case
    }

    public function getUnpublishedVersionsByLocator(array $locator)
    {

    }

    public function getPublishedVersionsByLocator(array $locator)
    {

    }
}
