<?php
namespace CarloNicora\Minimalism\Services\EventManager;

use CarloNicora\Minimalism\Abstracts\AbstractService;
use CarloNicora\Minimalism\Factories\ServiceFactory;
use CarloNicora\Minimalism\Interfaces\LoggerInterface;
use CarloNicora\Minimalism\Services\EventManager\Interfaces\EventInterface;
use CarloNicora\Minimalism\Services\EventManager\Interfaces\EventListenerInterface;
use CarloNicora\Minimalism\Services\RabbitMq\RabbitMq;
use Exception;

class EventManager extends AbstractService
{
    /** @var EventListenerInterface[]|null  */
    private ?array $listeners=null;

    /**
     * @param RabbitMq $rabbitMq
     * @param LoggerInterface $logger
     * @param string $MINIMALISM_SERVICE_EVENTMANAGER_LISTENERS
     * @param string|null $MINIMALISM_SERVICE_EVENTMANAGER_QUEUE
     */
    public function __construct(
        private readonly RabbitMq $rabbitMq,
        private readonly LoggerInterface $logger,
        private readonly string $MINIMALISM_SERVICE_EVENTMANAGER_LISTENERS,
        private readonly ?string $MINIMALISM_SERVICE_EVENTMANAGER_QUEUE='eventManager',
    )
    {
    }

    public function postIntialise(
        ServiceFactory $services,
    ): void
    {
        parent::postIntialise($services);

        $this->listeners = [];
        foreach (explode($this->MINIMALISM_SERVICE_EVENTMANAGER_LISTENERS, ',') as $listener){
            $this->listeners[] = $services->create($listener);
        }
    }

    /**
     * @return void
     */
    public function destroy(
    ): void
    {
        $this->listeners = null;
    }

    /**
     * @return RabbitMq
     */
    public function getQueueManager(
    ): RabbitMq
    {
        return $this->rabbitMq;
    }

    /**
     * @return string
     */
    public function getQueueName(
    ): string
    {
        return $this->MINIMALISM_SERVICE_EVENTMANAGER_QUEUE;
    }

    /**
     * @return EventListenerInterface[]|null
     */
    public function getListeners(
    ): ?array
    {
        return $this->listeners;
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function enqueueEvent(
        EventInterface $event,
    ): void
    {
        try {
            $this->rabbitMq->dispatchMessage(
                message: $event->exportMessage(),
                queueName: $this->MINIMALISM_SERVICE_EVENTMANAGER_QUEUE,
            );
        } catch (Exception $e) {
            $this->logger->error(
                message: $e->getMessage(),
                domain: 'Event Manager',
                context: ['type' => 'eventManager'],
            );
        }
    }
}