<?php

class Application_Form_Zmianahasla extends Zend_Form
{

    public function init()
    {
        $this->addElement('password','haslo1',array(
            'label'=>'Podaj haslo'
        ));
        $this->addElement('password','haslo2',array(
            'label'=>'Powtórz haslo'
        ));
        $this->addElement('submit','submit',array(
            'label'=>'Wyślij'
        ));
        
    }


}

