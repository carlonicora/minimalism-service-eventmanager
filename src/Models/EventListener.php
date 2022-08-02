<?php
namespace CarloNicora\Minimalism\Services\EventManager\Models;

use CarloNicora\Minimalism\Abstracts\AbstractModel;
use CarloNicora\Minimalism\Factories\MinimalismFactories;
use CarloNicora\Minimalism\Interfaces\LoggerInterface;
use CarloNicora\Minimalism\Services\EventManager\Bridges\Event;
use CarloNicora\Minimalism\Services\EventManager\EventManager;
use Exception;
use PhpAmqpLib\Message\AMQPMessage;

class EventListener extends AbstractModel
{
    public function __construct(
        MinimalismFactories               $minimalismFactories,
        ?string                           $function = null,
        private readonly ?EventManager    $eventManager=null,
        private readonly ?LoggerInterface $logger=null,
    )
    {
        parent::__construct($minimalismFactories, $function);
    }

    /**
     * @return never
     * @throws Exception
     */
    public function cli(
    ): never
    {
        $callable = [$this, 'processMessage'];
        $this->eventManager->getQueueManager()->listen(
            callback: $callable,
            queueName: $this->eventManager->getQueueName(),
        );

        exit;
    }

    /**
     * @param AMQPMessage $message
     * @return void
     */
    protected function processMessage(
        AMQPMessage $message,
    ): void
    {
        try {
            $event = new Event();
            $event->importMessage($message->body);
        } catch (Exception $e) {
            $event = null;
            $this->logger->error(
                message: $e->getMessage(),
                domain: 'Event Manager',
                context: ['type' => 'eventListener'],
            );
        }

        if ($event !== null) {
            foreach ($this->eventManager->getListeners() as $listener) {
                $listener->processEvent($event);
            }
        }
    }
}