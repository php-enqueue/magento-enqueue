<?php

use Enqueue\Client\Message;
use Enqueue\Psr\PsrProcessor;

class Enqueue_Enqueue_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * @var \Enqueue\Client\SimpleClient
     */
    private $client;

    /**
     * @var \Enqueue\Client\ProducerInterface
     */
    private $producer;

    public function bindProcessors()
    {
        if (false == $processors = Mage::getStoreConfig('enqueue/processors')) {
            return;
        }

        foreach ($processors as $name => $config) {
            if (empty($config['topic'])) {
                throw new \LogicException(sprintf('Topic name is not set for processor: "%s"', $name));
            }

            if (empty($config['helper'])) {
                throw new \LogicException(sprintf('Helper name is not set for processor: "%s"', $name));
            }

            $this->getClient()->bind($config['topic'], $name, function () use ($config) {
                $processor = Mage::helper($config['helper']);

                if (false == $processor instanceof PsrProcessor) {
                    throw new \LogicException(sprintf('Expects processor is instance of: "%s"', PsrProcessor::class));
                }

                call_user_func_array([$processor, 'process'], func_get_args());
            });
        }
    }

    /**
     * @param string               $topic
     * @param string|array|Message $message
     */
    public function send($topic, $message)
    {
        $this->getProducer()->send($topic, $message);
    }

    /**
     * @return \Enqueue\Client\ProducerInterface
     */
    public function getProducer()
    {
        if (null === $this->producer) {
            $this->producer = $this->getClient()->getProducer();
        }

        return $this->producer;
    }

    /**
     * @return \Enqueue\Client\SimpleClient
     */
    public function getClient()
    {
        if (null === $this->client) {
            $name = Mage::getStoreConfig('enqueue/transport/default');

            switch ($name) {
                case 'rabbitmq-amqp':
                    $this->client = $this->buildRabbitMqAmqp();
                    break;
                default:
                    throw new \LogicException(sprintf('Unknown transport: "%s"', $name));
            }
        }

        return $this->client;
    }

    /**
     * @return \Enqueue\Client\SimpleClient
     */
    public function buildRabbitMqAmqp()
    {
        $config = [
            'host' => Mage::getStoreConfig('enqueue/rabbitmq/host'),
            'port' => Mage::getStoreConfig('enqueue/rabbitmq/port'),
            'login' => Mage::getStoreConfig('enqueue/rabbitmq/login'),
            'password' => Mage::getStoreConfig('enqueue/rabbitmq/password'),
            'vhost' => Mage::getStoreConfig('enqueue/rabbitmq/vhost'),
        ];

        $connectionFactory = new \Enqueue\AmqpExt\AmqpConnectionFactory($config);

        return new \Enqueue\Client\SimpleClient(
            $connectionFactory->createContext(),
            $this->buildConfig()
        );
    }

    /**
     * @return \Enqueue\Client\Config
     */
    public function buildConfig()
    {
        return new \Enqueue\Client\Config(
            Mage::getStoreConfig('enqueue/client/prefix'),
            Mage::getStoreConfig('enqueue/client/app_name'),
            Mage::getStoreConfig('enqueue/client/router_topic'),
            Mage::getStoreConfig('enqueue/client/router_queue'),
            Mage::getStoreConfig('enqueue/client/default_processor_queue'),
            'enqueue-router-processor'
        );
    }
}
