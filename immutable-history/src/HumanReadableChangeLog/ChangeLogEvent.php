<?php

namespace Rcm\ImmutableHistory\HumanReadableChangeLog;

class ChangeLogEvent
{
    /**
     * @var \DateTime
     */
    protected $date;
    /**
     * @var string
     */
    protected $userId;

    /**
     * @var string
     */
    protected $actionDescription;

    /**
     * @var string
     */
    protected $resourceDescription;
    /**
     * @var array
     */
    protected $metaData = [];

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void
    {
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string
     */
    public function getActionDescription(): string
    {
        return $this->actionDescription;
    }

    /**
     * @param string $actionDescription
     */
    public function setActionDescription(string $actionDescription): void
    {
        $this->actionDescription = $actionDescription;
    }

    /**
     * @return string
     */
    public function getResourceDescription(): string
    {
        return $this->resourceDescription;
    }

    /**
     * @param string $resourceDescription
     */
    public function setResourceDescription(string $resourceDescription): void
    {
        $this->resourceDescription = $resourceDescription;
    }

    /**
     * @return array
     */
    public function getMetaData(): array
    {
        return $this->metaData;
    }

    /**
     * @param array $metaData
     */
    public function setMetaData(array $metaData): void
    {
        $this->metaData = $metaData;
    }
}