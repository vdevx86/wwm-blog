<?php
/**
 * Copyright © 2017 Walk with Magento
 * See COPYING.txt for license details
 */

/**
 * Free integration between Magento 2 and WordPress
 *
 * @author Ovakimyan Vazgen <vdevx86job@gmail.com>
 * @copyright 2017 Walk with Magento (http://wwm-integrations.in.ua)
 * @license https://opensource.org/licenses/OSL-3.0 Open Software License ("OSL") v. 3.0
 * @copyright 2017 Ovakimyan Vazgen <vdevx86job@gmail.com>
 */

namespace Wwm\Blog\Block\Adminhtml\Form\Field;

class Install extends \Magento\Framework\Data\Form\Element\Button
{
    
    protected $field;
    
    public function __construct(
        \Magento\Framework\Data\Form\Element\Factory $factoryElement,
        \Magento\Framework\Data\Form\Element\CollectionFactory $factoryCollection,
        \Magento\Framework\Escaper $escaper,
        \Wwm\Blog\Block\System\Config\Form\Field\Install $field,
        $data = []
    ) {
        parent::__construct(
            $factoryElement,
            $factoryCollection,
            $escaper,
            $data
        );
        $this->field = $field;
    }
    
    public function getElementHtml()
    {
        $result  = $this->getBeforeElementHtml();
        $result .= $this->field->toHtml();
        $result .= $this->getAfterElementJs();
        $result .= $this->getAfterElementHtml();
        return $result;
    }
    
}