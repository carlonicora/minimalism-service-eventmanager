<?php
namespace CarloNicora\Minimalism\Services\EventManager\Interfaces;

interface EventInterface
{
    /**
     * @param string|null $type
     * @param int|null $actorId
     * @param int|null $resourceId
     */
    public function __construct(
        ?string $type=null,
        ?int $actorId=null,
        ?int $resourceId=null,
    );

    /**
     * @return array
     */
    public function exportMessage(
    ): array;

    /**
     * @param string $message
     * @return void
     */
    public function importMessage(
        string $message,
    ): void;

    /**
     * @return string
     */
    public function getType(
    ): string;

    /**
     * @param string $type
     * @return void
     */
    public function setType(
        string $type,
    ): void;

    /**
     * @return int
     */
    public function getActorId(
    ): int;

    /**
     * @param int $actorId
     */
    public function setActorId(
        int $actorId,
    ): void;

    /**
     * @return int
     */
    public function getResourceId(
    ): int;

    /**
     * @param int $resourceId
     * @return void
     */
    public function setResourceId(
        int $resourceId,
    ): void;

    /**
     * @return int
     */
    public function getCreatedAt(
    ): int;
}