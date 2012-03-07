<?php

class Application_Form_Link extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->addElement('text','link',array(
            'label'=>'Podaj link do skrucenia',
            'value'=>'http://'            
        ));
        $this->addElement('submit','submit',array(
            'label'=>'Dodaj'
        ));
    }


}

