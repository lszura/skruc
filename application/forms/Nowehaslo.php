<?php

class Application_Form_Nowehaslo extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->addElement('text','email',array(
            'label'=>'Podaj email'
        ));
        $this->addElement('submit','submit',array(
            'label'=>'Wyślij'
        ));
    }


}

