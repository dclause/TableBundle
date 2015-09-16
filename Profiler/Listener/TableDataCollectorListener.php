<?php

namespace EMC\TableBundle\Profiler\Listener;

use EMC\TableBundle\Event\TablePreSetDataEvent;
use EMC\TableBundle\Event\TablePostSetDataEvent;
use EMC\TableBundle\Profiler\TableDataCollector;

/**
 * Description of TableDataCollectorListener
 *
 * @author emc
 */
class TableDataCollectorListener {

    /**
     * @var TableDataCollector
     */
    private $dataCollector;
    
    function __construct(TableDataCollector $dataCollector) {
        $this->dataCollector = $dataCollector;
    }
    
    public function onPreSetData(TablePreSetDataEvent $event) {
        
    }
    
    public function onPostSetData(TablePostSetDataEvent $event) {
        $this->dataCollector->collectConfig($event->getTable(), $event->getData(), $event->getOptions());
    }
}