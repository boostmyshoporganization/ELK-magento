<?php

class Devart_ElkMagento_Model_Observer
{
    
    const LOG_FILE = 'elk-magento.log';
   
    protected $startTime;
    protected $startMemory;

    public function preDispatch()
    {
        $this->startTime   = microtime(true);
        $this->startMemory = memory_get_usage(true);
    }

    public function postDispatch(Varien_Event_Observer $observer)
    {
        $event = $observer->getEvent();

        $action = $event->getControllerAction()->getFullActionName();
        $time   = round(microtime(true) - $this->startTime, 2);
        $memory = memory_get_usage(true) - $this->startMemory;

        $message = Mage::getDesign()->getArea() . ' | ' . $action . ' | ' . $time . ' | ' . $memory;

        Mage::log($message, null, self::LOG_FILE);
    }
}
