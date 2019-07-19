<?php

namespace EMC\TableBundle\Table\Column\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Text Column
 *
 * @author Chafiq El Mechrafi <chafiq.elmechrafi@gmail.com>
 */
class TextType extends AnchorType {
    
    /**
     * {@inheritdoc}
     * <br/>
     * <br/>
     * Available Options :
     * <ul>
     * <li><b>anchor_route</b>  : string|null <i>Anchor route, default null.</i></li>
     * </ul>
     */
    public function setDefaultOptions(OptionsResolver $resolver, array $defaultOptions) {
        parent::setDefaultOptions($resolver, $defaultOptions);

        $resolver->setAllowedTypes('anchor_route', array('null', 'string'));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'text';
    }

}
