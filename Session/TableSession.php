<?php

namespace EMC\TableBundle\Session;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use EMC\TableBundle\Table\TableTypeInterface;

/**
 * TableSession manage tables in the session
 *
 * @author Chafiq El Mechrafi <chafiq.elmechrafi@gmail.com>
 */
class TableSession implements TableSessionInterface {

    /**
     * @var SessionInterface
     */
    private $session;

    function __construct(SessionInterface $session) {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function restore($tableId) {
        if (($tables = $this->session->get('tables', null)) === null || !isset($tables[$tableId])) {
            throw new \InvalidArgumentException;
        }

        return $tables[$tableId];
    }

    /**
     * {@inheritdoc}
     */
    public function store(TableTypeInterface $type, array $data = null, array $options = array()) {

        if (!isset($options['_tid'])) {
            throw new \RuntimeException;
        }

        $tables = $this->session->get('tables');

        $tid = $options['_tid'];

        foreach ($options as $option => $_) {
            if (substr($option, 0, 1) === '_') {
                unset($options[$option]);
            }
        }

        $tables[$tid] = array(
            'class' => get_class($type),
            'data' => $data,
            'options' => $options
        );

        $this->session->set('tables', $tables);
    }

}
