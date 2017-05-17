<?php

namespace Sylake\SyliusConsumerPlugin\Denormalizer;

use Sylake\SyliusConsumerPlugin\Event\TaxonCreated;
use PhpAmqpLib\Message\AMQPMessage;
use SyliusLabs\RabbitMqSimpleBusBundle\Denormalizer\DenormalizerInterface;

final class TaxonCreatedDenormalizer implements DenormalizerInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports(AMQPMessage $message)
    {
        $decodedMessage = json_decode($message->getBody(), true);

        if (
            isset($decodedMessage['type']) &&
            MessageType::ASSOCIATION_TYPE_CREATED_MESSAGE_TYPE === $decodedMessage['type'] &&
            $message->get('content_type') === 'json'
        ) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize(AMQPMessage $message)
    {
        $message = json_decode($message->getBody(), true);

        return new TaxonCreated($message['code'], $message['parent'], $message['labels']);
    }
}