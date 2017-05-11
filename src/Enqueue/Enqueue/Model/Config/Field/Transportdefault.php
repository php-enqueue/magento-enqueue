<?php
use Enqueue\AmqpExt\AmqpContext;
use Enqueue\Stomp\StompContext;
use Enqueue\Fs\FsContext;
use Enqueue\Sqs\SqsContext;
use Enqueue\Redis\RedisContext;
use Enqueue\Dbal\DbalContext;

class Enqueue_Enqueue_Model_Config_Field_Transportdefault extends Mage_Core_Model_Config_Data
{
    /**
     * {@inheritdoc}
     */
    protected function _beforeSave()
    {
        $return = parent::_beforeSave();

        $transport = $this->getValue();

        $data = [
            'rabbitmq_amqp' => [
                'name' => 'RabbitMQ AMQP',
                'package' => 'enqueue/amqp-ext',
                'class' => AmqpContext::class,
            ],
            'amqp' => [
                'name' => 'AMQP',
                'package' => 'enqueue/amqp-ext',
                'class' => AmqpContext::class,
            ],
            'rabbitmq_stomp' => [
                'name' => 'RabbitMQ STOMP',
                'package' => 'enqueue/stomp',
                'class' => StompContext::class,
            ],
            'stomp' => [
                'name' => 'STOMP',
                'package' => 'enqueue/stomp',
                'class' => StompContext::class,
            ],
            'fs' => [
                'name' => 'Filesystem',
                'package' => 'enqueue/fs',
                'class' => FsContext::class,
            ],
            'sqs' => [
                'name' => 'Amazon AWS SQS',
                'package' => 'enqueue/sqs',
                'class' => SqsContext::class,
            ],
            'redis' => [
                'name' => 'Redis',
                'package' => 'enqueue/redis',
                'class' => RedisContext::class,
            ],
            'dbal' => [
                'name' => 'Doctrine DBAL',
                'package' => 'enqueue/dbal',
                'class' => DbalContext::class,
            ],
        ];

        if (false == isset($data[$transport])) {
            throw new \LogicException(sprintf('Unknown transport: "%s"', $transport));
        }

        if (false == $this->isClassExists($data[$transport]['class'])) {
            Mage::throwException(sprintf('%s transport requires package "%s". Please install it via composer. #> php composer.php require %s',
                $data[$transport]['name'], $data[$transport]['package'], $data[$transport]['package']
            ));
        }

        return $return;
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function isClassExists($class)
    {
        try {
            return class_exists($class);
        } catch (\Exception $e) { // in dev mode error handler throws exception
            return false;
        }
    }
}
