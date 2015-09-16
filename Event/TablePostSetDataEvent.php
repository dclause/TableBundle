<?php

namespace EMC\TableBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use EMC\TableBundle\Table\TableInterface;

/**
 * Description of TablePostSetDataEvent
 *
 * @author emc
 */
class TablePostSetDataEvent extends Event {
    
    const NAME = 'table.post_set_data';

    /**
     * @var TableInterface
     */
    private $table;
    
    /**
     * @var mixed
     */
    private $data;
    
    /**
     * @var array
     */
    private $options;

    function __construct(TableInterface $table, $data = null, array $options = array()) {
        $this->table = $table;
        $this->data = $data;
        $this->options = $options;
    }

    public function getTable() {
        return $this->table;
    }

    public function getData() {
        return $this->data;
    }

    public function getOptions() {
        return $this->options;
    }

}
