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
            ['value' => 'rabbitmq-amqp', 'label' => Mage::helper('enqueue')->__('RabbitMQ AMQP')],
        ];
    }
}
