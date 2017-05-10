<?php

class Enqueue_Enqueue_Model_Config_Source_Transport
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'rabbitmq_amqp', 'label' => Mage::helper('enqueue')->__('RabbitMQ AMQP')],
            ['value' => 'amqp', 'label' => Mage::helper('enqueue')->__('AMQP')],
            ['value' => 'rabbitmq_stomp', 'label' => Mage::helper('enqueue')->__('RabbitMQ STOMP')],
            ['value' => 'stomp', 'label' => Mage::helper('enqueue')->__('STOMP')],
            ['value' => 'fs', 'label' => Mage::helper('enqueue')->__('Filesystem')],
            ['value' => 'sqs', 'label' => Mage::helper('enqueue')->__('Amazon AWS SQS')],
            ['value' => 'redis', 'label' => Mage::helper('enqueue')->__('Redis')],
            ['value' => 'dbal', 'label' => Mage::helper('enqueue')->__('Doctrine DBAL')],
        ];
    }
}
