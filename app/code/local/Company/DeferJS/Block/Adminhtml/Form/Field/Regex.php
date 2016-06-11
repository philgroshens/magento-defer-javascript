<?php

class Company_DeferJS_Block_Adminhtml_Form_Field_Regex extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract {

    public function __construct() {
        $this->addColumn('regexp', array(
            'label' => Mage::helper('adminhtml')->__('Matched Expression'),
            'style' => 'width:100px',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = Mage::helper('adminhtml')->__('Add Match');
        parent::__construct();
    }

}
