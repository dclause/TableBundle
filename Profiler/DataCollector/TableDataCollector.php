<?php

namespace EMC\TableBundle\Profiler\DataCollector;

use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EMC\TableBundle\Table\TableInterface;
use EMC\TableBundle\Table\Column\ColumnInterface;
use Symfony\Component\VarDumper\Cloner\VarCloner;

/**
 * TableDataCollector
 * 
 * This class collect data for the WebProfiler
 *
 * @author Chafiq El Mechrafi <chafiq.elmechrafi@gmail.com>
 */
class TableDataCollector extends DataCollector {

    /**
     * @var VarCloner
     */
    private $exporter;

    function __construct(VarCloner $exporter = null) {
        $this->exporter = $exporter ? : new VarCloner();
    }

	/**
	 * {@inheritdoc}
	 */
	public function reset()
	{
		$this->data = array(
			'tables' => array()
		);
	}

    public function collect(Request $request, Response $response, \Exception $exception = null) {
        
    }

    public function collectConfig(TableInterface $table, $data = null, array $options = array()) {
        if (!isset($options['_tid'])) {
            throw new \RuntimeException;
        }
        $this->init();
        $this->data['tables'][$options['_tid']] = $this->extractConfig($table, $options, $options);
    }

    private function extractConfig(TableInterface $table, array $data = null, array $options = array()) {
        $data = array(
            'id' => $table->getOption('name'),
            'name' => $table->getOption('name'),
            'type' => $table->getType()->getName(),
            'type_class' => get_class($table->getType()),
            'passed_options' => array(),
            'resolved_options'  => array(),
            'columns'           => array()
        );
        
        foreach ($table->getOptions() as $option => $value) {
            if (substr($option, 0, 1) !== '_') {
                $data['resolved_options'][$option] = $this->exporter->cloneVar($value);
            }
        }
        
        foreach( $options['_passed_options'] as $option => $value ) {
            $data['passed_options'][$option] = $this->exporter->cloneVar($value);
        }
        
        ksort($data['passed_options']);
        ksort($data['resolved_options']);

        /* @var $column \EMC\TableBundle\Table\Column\ColumnInterface */
        $column = null;
        foreach( $table->getColumns() as $column ) {
            $data['columns'][$column->getOption('name')] = $this->extractColumnConfig($column);
        }
        
        return $data;
    }
    
    private function extractColumnConfig(ColumnInterface $column) {
        $data = array(
            'id' => $column->getOption('name'),
            'name' => $column->getOption('name'),
            'type' => $column->getType()->getName(),
            'type_class' => get_class($column->getType()),
            'passed_options' => array(),
            'resolved_options'  => array()
        );
        
        foreach ($column->getOptions() as $option => $value) {
            if (substr($option, 0, 1) !== '_') {
                $data['resolved_options'][$option] = $this->exporter->cloneVar($value);
            }
        }
        
        foreach( $column->getOption('_passed_options') as $option => $value ) {
            $data['passed_options'][$option] = $this->exporter->cloneVar($value);
        }
        
        return $data;
    }

    public function collectData(TableInterface $table, $data = null, array $options = array()) {

        if (!isset($options['_tid'])) {
            throw new \RuntimeException;
        }

        $id = $options['_tid'];
        
        $this->init();
        
        if (!isset($this->data['tables'][$id])) {
            $this->extractConfig($table, $data, $options);
        }
        
        $data = &$this->data['tables'][$id];

        $data['query_result'] = array(
            'query' => $this->exporter->cloneVar($options['_query']),
            'total' => $table->getData()->getCount()
        );
        
        $rows = $table->getData()->getRows();
        $count = count($rows);
        for( $idx=0; $idx<$count; $idx++){
            $row = $rows[$idx]; 
            $data['query_result']['rows ' . $idx] = $this->exporter->cloneVar($row);
            if ( $idx === 10 ) {
                $data['query_result']['...'] = '';
                $idx = max($idx, count($rows) - 4);
            }
        }
        unset($data);
    }

    protected function init() {
        if (isset($this->data['tables'])) {
            return;
        }

        $this->data = array(
            'tables' => array()
        );
    }

    public function getTables() {
        return isset($this->data['tables']) ? $this->data['tables'] : array();
    }

    public function getName() {
        return 'table';
    }

}
