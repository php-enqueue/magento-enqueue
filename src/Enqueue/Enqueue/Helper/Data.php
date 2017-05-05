<?php

class Enqueue_Enqueue_Helper_Data extends Mage_Core_Helper_Data
{
    /**
     * @var \Enqueue\Client\SimpleClient
     */
    private $client;

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
