<?php

class Application_Form_Szukaj extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->addElement('text','szukaj');
    }


}

