<?php
namespace CarloNicora\Minimalism\Services\EventManager\Interfaces;

interface EventListenerInterface
{
    /**
     * @param EventInterface $event
     * @return void
     */
    public function processEvent(
        EventInterface $event,
    ): void;
}