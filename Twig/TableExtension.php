<?php

namespace EMC\TableBundle\Twig;

use EMC\TableBundle\Table\TableView;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\Template;
use Twig\TwigFunction;

/**
 * TableExtension
 * 
 * @author Chafiq El Mechrafi <chafiq.elmechrafi@gmail.com>
 */
class TableExtension extends AbstractExtension {

    /**
     * @var Environment 
     */
    private $environment;

    /**
     * @var \Twig_Template
     */
    private $template;

    /**
     * @var array
     */
    private $extensions;

    function __construct(Environment $environment, $template, array $extensions) {
        $this->environment = $environment;
        $this->template = $template;
        $this->extensions = $extensions;
    }

    public function load() {
        if ($this->template instanceof Template) {
            return;
        }

        $this->template = $this->environment->loadTemplate($this->template);
        $extensions = array();
        foreach ($this->extensions as $extension) {
            $extensions = array_merge(
                    $extensions, $this->environment->loadTemplate($extension)->getBlocks()
            );
        }
        $this->extensions = $extensions;
    }

    public function getFunctions() {
        return array(
            'table' => new TwigFunction('table', [$this, 'table'], array(
                'is_safe' => array('all'),
                'needs_environment' => true
                    )),
            'table_rows' => new TwigFunction('table_rows', [$this, 'rows'], array(
                'is_safe' => array('all'),
                'needs_environment' => true
                    )),
            'table_pages' => new TwigFunction('table_pages', [$this, 'pages'], array(
                'is_safe' => array('all'),
                'needs_environment' => true
                    )),
            'table_cell' => new TwigFunction('table_cell', [$this, 'cell'], array(
                'is_safe' => array('all'),
                'needs_environment' => true
                    )),
            'camel_case_to_option' => new TwigFunction('camel_case_to_option', [$this, 'camelCaseToOption'], array(
                'is_safe' => array('all')
                    ))
        );
    }

    /**
     * Render block $block with $table view's data.
     * @param Environment $twig
     * @param \EMC\TableBundle\Table\TableView $view
     * @param string $block
     * @return string
     */
    public function render(Environment $twig, TableView $view, $block) {
        $this->load();
        return $this->template->renderBlock($block, $view->getData());
    }

    /**
     * @see TableExtension::render
     */
    public function table(Environment $twig, TableView $view) {
        return $this->render($twig, $view, 'table');
    }

    /**
     * @see TableExtension::render
     */
    public function rows(Environment $twig, TableView $view) {
        return $this->render($twig, $view, 'rows');
    }

    /**
     * @see TableExtension::render
     */
    public function pages(Environment $twig, TableView $view) {
        return $this->render($twig, $view, 'pages');
    }

    /**
     * @see TableExtension::render
     */
    public function cell(Environment $twig, array $data) {
        $this->load();
        return $this->getBlock($data['type'])->renderBlock($data['type'] . '_widget', $data);
    }

    /**
     * Transform camel case to DOM data option : subTableId => sub-table-id
     * @param string $option
     * @return string
     */
    public function camelCaseToOption($option) {
        return strtolower(preg_replace('/(?<=\\w)(?=[A-Z])/', '-$1', $option));
    }

    /**
     * Return the block template
     * @param string $type
     * @return \Twig_Template
     * @throws \InvalidArgumentException
     */
    private function getBlock($type) {
        if (!isset($this->extensions[$type . '_widget'])) {
            throw new \InvalidArgumentException('Block ' . $type . '_widget for the column type ' . $type . ' not found');
        }
        return $this->extensions[$type . '_widget'][0];
    }

    public function getName() {
        return 'table_extension';
    }

}
