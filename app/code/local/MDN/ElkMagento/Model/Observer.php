<?php

class MDN_ElkMagento_Model_Observer
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

        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'cli';
        $action = $event->getControllerAction()->getFullActionName();
        $time   = round(microtime(true) - $this->startTime, 2);
        $memory = memory_get_usage(true) - $this->startMemory;

        $message = '['.date('c').'] magento | ' . $method . ' | ' . $action . ' | ' . $time . ' | ' . $memory."\n";

        file_put_contents(Mage::getBaseDir('log').'/'.self::LOG_FILE, $message, FILE_APPEND);
    }
}
