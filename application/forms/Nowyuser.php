<?php

class Application_Form_Nowyuser extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
       

        
        $imie = $this->createElement('text','imie');
        $imie->setLabel('Imię:');
        $nazwisko =$this->createElement('text','nazwisko');
        $nazwisko->setLabel('Nazwisko:');
        $email_u = $this->createElement('text','email_u');
        $email_u->setLabel('Adres email:');
        $haslo = $this->createElement('password','haslo');
        $haslo->setLabel('Podaj hasło:');
        $haslo1 = $this->createElement('password','haslo1');
        $haslo1->setLabel('Powtórz hasło:');
        $captcha = $this->createElement('captcha', 'captcha',
                array('required' => true,
                        'captcha' => array('captcha' => 'Image',
                        'font' => APPLICATION_PATH.'/../public/font/verdana.ttf',
                        'fontSize' => '24',
                        'wordLen' => 5,
                        'height' => '50',
                        'width' => '150',
                        'imgDir' => APPLICATION_PATH.'/../public/captcha',
                        'imgUrl' => Zend_Controller_Front::getInstance()->getBaseUrl().
                        '/captcha',
                        'dotNoiseLevel' => 50,
                        'lineNoiseLevel' => 5)));

       $captcha->setLabel('Wprowadż kod z obrazka:');
       $submit = new Zend_Form_Element_Submit('submit');
       $submit->setValue('submit');
       $submit->setLabel('Utwórz');
       


               
               

        $this->addElements(array(
            $imie,
            $nazwisko,
            $email_u,
            $haslo,
            $haslo1,
            $captcha,
            $submit
            
        ));
        $this->setAction('')
	        ->setMethod('post');
        
       
        $this->setElementDecorators(array(
            'ViewHelper',
            array(array('data' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element')),
            array('Label', array('tag' => 'td')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
        ));
        $submit->setDecorators(array('ViewHelper',
            array(array('data' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element')),
            array(array('emptyrow' => 'HtmlTag'),  array('tag' =>'td', 'class'=> 'element', 'placement' => 'PREPEND')),
            array(array('row' => 'HtmlTag'), array('tag' => 'tr'))
            ));
        $captcha->setDecorators(array(
      //      'Description',
     //       'Errors',
            array(array('data'=>'HtmlTag'),array('tag'=>'td','class'=> 'element')),
            array('Label',array('tag'=>'td')),
            array(array('row'=>'HtmlTag'),array('tag'=>'tr'))
        ));
        
        $this->setDecorators(array(
            'FormElements',
            array('HtmlTag', array('tag' => 'table')),
            'Form'
        ));



        
                
    }

 

}

