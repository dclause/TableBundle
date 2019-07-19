<?php

namespace EMC\TableBundle\Table\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use EMC\TableBundle\Table\Column\ColumnInterface;

/**
 * Button Column
 *
 * @author Chafiq El Mechrafi <chafiq.elmechrafi@gmail.com>
 */
class ButtonType extends IconType {

    /**
     * {@inheritdoc}
     */
    public function buildView(array &$view, ColumnInterface $column, array $data, array $options) {
        if ( $options['icon'] !== null ) {
            parent::buildView($view, $column, $data, $options);
        } else {
            ColumnType::buildView($view, $column, $data, $options);
        }
        
        $view['text'] = isset($options['text']) ? $options['text'] : $view['value'];
        $view['title'] = $options['desc'];
    }

    /**
     * {@inheritdoc}
     * <br/>
     * <br/>
     * Available Options :
     * <ul>
     * <li><b>text</b>          : string|null <i>Button text. If null $view['value'] replace it.</i></li>
     * <li><b>desc</b>          : string|null <i>Button title</i></li>
     * </ul>
     */
    public function setDefaultOptions(OptionsResolver $resolver, array $defaultOptions) {
        parent::setDefaultOptions($resolver, $defaultOptions);

        $resolver->setDefaults(array(
            'text' => null,
            'desc' => null
        ));

        $resolver->setAllowedTypes('text', array('null', 'string'));
        $resolver->setAllowedTypes('anchor_route', array('null')); /* Button is not an anchor */
        $resolver->setAllowedTypes('icon', array('null', 'string', 'callable'));
        $resolver->setAllowedTypes('desc', array('null', 'string'));
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'button';
    }

}
