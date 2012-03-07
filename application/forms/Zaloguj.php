<?php

class Application_Form_Zaloguj extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $email = $this->createElement('text', 'email');
        $haslo = $this->createElement('password', 'haslo');
        $zapamietaj = $this->createElement('checkbox', 'remember');
        //$zapamietaj->setLabel("Zapamiętaj mnie");
        $submit = new Zend_Form_Element_Submit('Zaloguj');
        $submit->setValue('Zaloguj');
     //   $submit->setLabel('Zaloguj');
        $this->addElements(array(
            $email,
            $haslo,
            $zapamietaj,
            $submit
        ));
        
      
        


       
        
    }


}

