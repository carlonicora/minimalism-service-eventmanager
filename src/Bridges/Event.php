<?php
namespace CarloNicora\Minimalism\Services\EventManager\Bridges;

use CarloNicora\Minimalism\Services\EventManager\Interfaces\EventInterface;
use Exception;

class Event implements EventInterface
{
    /** @var string  */
    private string $type;

    /** @var int  */
    private int $actorId;

    /** @var int  */
    private int $resourceId;

    /** @var int  */
    private int $createdAt;

    /**
     * @param string|null $type
     * @param int|null $actorId
     * @param int|null $resourceId
     */
    public function __construct(
        ?string $type = null,
        ?int $actorId = null,
        ?int $resourceId = null,
    )
    {
        if ($type !== null){
            $this->type = $type;
        }

        if ($actorId !== null){
            $this->actorId = $actorId;
        }

        if ($resourceId !== null){
            $this->resourceId = $resourceId;
        }

        $this->createdAt = time();
    }

    /**
     * @return array
     */
    public function exportMessage(
    ): array
    {
        return [
            'type' => $this->type,
            'actorId' => $this->actorId,
            'resourceId' => $this->resourceId,
            'createdAt' => $this->createdAt,
        ];
    }

    /**
     * @param string $message
     * @return void
     * @throws Exception
     */
    public function importMessage(
        string $message,
    ): void
    {
        $messageContent = json_decode($message, true, JSON_THROW_ON_ERROR, JSON_THROW_ON_ERROR);

        $this->type = $messageContent['type'];
        $this->actorId = $messageContent['actorId'];
        $this->resourceId = $messageContent['resourceId'];
        $this->createdAt = $messageContent['createdAt'];
    }

    /**
     * @return string
     */
    public function getType(
    ): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(
        string $type,
    ): void
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getActorId(
    ): int
    {
        return $this->actorId;
    }

    /**
     * @param int $actorId
     */
    public function setActorId(
        int $actorId,
    ): void
    {
        $this->actorId = $actorId;
    }

    /**
     * @return int
     */
    public function getResourceId(
    ): int
    {
        return $this->resourceId;
    }

    /**
     * @param int $resourceId
     * @return void
     */
    public function setResourceId(
        int $resourceId,
    ): void
    {
        $this->resourceId = $resourceId;
    }

    /**
     * @return int
     */
    public function getCreatedAt(
    ): int
    {
        return $this->createdAt;
    }
}