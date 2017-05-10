<?php

class Enqueue_Enqueue_Model_Config_Source_Redis_Vendor
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'phpredis', 'label' => Mage::helper('enqueue')->__('phpredis')],
            ['value' => 'predis', 'label' => Mage::helper('enqueue')->__('predis')],
        ];
    }
}
