<?php

class LogowanieController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        
    }

    public function indexAction()
    {
        // action body
    }

    public function nowyuserAction()
    {
        // action body
       
        $nowy   =   new Application_Form_Nowyuser();
        $nowy->setAction('/public/logowanie/nowyuser')
	          ->setMethod('post');
        if($this->getRequest()->isPost()){
            //if(isset($captcha['input'])){
            //var_dump($this->getRequest()->getPost('captcha'));
            $captcha            =   $this->getRequest()->getPost('captcha');
            $captchaSession     =   new Zend_Session_Namespace('Zend_Form_Captcha_'.$captcha['id']);
            $captchaIterator    =   $captchaSession->getIterator();
            $captchaWord        =   $captchaIterator['word'];
                
                if($captcha['input'] == $captchaWord)  
                  {  
                  $nowyu = new Model_DbTable_User();
                  $nowyu->insert(array(
                  'u_imie'=>$_POST['imie'],
                  'u_nazwisko'=>$_POST['nazwisko'],
                  'haslo'=>hash('sha256',$_POST['haslo']),
                      'email'=>$_POST['email_u'],
                      'status'=>'nowy'
                  ));
                  
                  if(isset($_COOKIE['url']))
                  {
                      $db_link    =   new Model_DbTable_Link();
                      $id         =   $nowyu->getId($_POST['email_u']);
                      $link       =   $_COOKIE['url'];
                      $db_link->addlink($id, $link);
                  }
                  
                  $mail = new Zend_Mail('UTF-8');
                  $mail->setSubject("Aktywacja konta dla ".$_POST['email_u'])
                      ->addTo($_POST['email_u'])
                      ->setBodyHtml("<h1>Witaj</h1>
Dziękujemy za rejestracje, aby w pełni korzystać z aplikacji kliknij na link aktywacyjny <br>
<a href='http://".$_SERVER['SERVER_NAME'].$this->getRequest()->getBaseURL()."/logowanie/potwierdzenie/user/"
.$_POST['email_u']."/kod/".hash('sha256',$_POST['email_u'].'exrc12')."'>
link</a>")
                          ->send();
                      
                  setcookie('message','Użytkownik został zarejestrowany, proszę o sprawdzenie emaila',0,'/');
                  $this->_redirect('/index');    
                  }else{
                      $this->view->error  =   "Błędny kod z obrazka";
                  }
            
        }
       // }
        $this->view->nowy=$nowy;
        
    }
    

    public function zalogujAction()
    {
       $form = new Application_Form_Zaloguj();
       $form->setAction('/public/logowanie/zaloguj')
               ->setMethod('POST');
       
       if($this->getRequest()->isPost()){
            if($form->isValid($this->getRequest()->getPost())){
                if(!Zend_Auth::getInstance()->hasIdentity()){
                    $db         =    Zend_Db_Table::getDefaultAdapter();
                    $log        =    new Zend_Auth_Adapter_DbTable($db,'user','email','haslo');
                    $log->setIdentity($form->getValue('email'));
                    $log->setCredential(hash('sha256', $form->getValue('haslo')));
                    $rezultat   =    $log->authenticate();
                    if($rezultat->isValid()){
                       $sesja   =    Zend_Auth::getInstance();
                       $dane    =    $sesja->getStorage();
                       $dane->write($log->getResultRowObject(array(
                            'id','u_imie','u_nazwisko','email','status'
                            )));
                       if($form->getValue('remember') == 1)
                            Zend_Session::rememberMe();
                        
                       $this->_redirect('/index');
                       }
                    else{
                        $this->view->logmassage='error';
                        setcookie('error-log','Błędne hasło lub login',0,'/');
                        $this->_redirect('/index');
                        
                }
                
            }
      }}
      $this->view->form=$form;
    }

    public function wylogujAction()
    {
        Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
        $this->_redirect('/index');
        
    }

    public function nowehasloAction()
    {
        $form_new_haslo = new Application_Form_Nowehaslo();
        $form_new_haslo->setAction('/public/logowanie/nowehaslo')
                ->setMethod('post');
        if($this->getRequest()->isPost()){
            if($form_new_haslo->isValid($this->getRequest()->getPost())){
                $db_user = new Model_DbTable_User();
                $email = $form_new_haslo->getValue('email');
                if($db_user->finduser($email)){
                    $mail = new Zend_Mail('UTF-8');
                    $html = "<h1>Witaj</h1>
Kliknij na link aby zmienić hasło:<br>
<a href='http://".$_SERVER['SERVER_NAME'].$this->getRequest()->getBaseURL()."/logowanie/zmianahasla/user/"
.$email."/kod/".hash('sha256',$email.'exrc1234').
"'>link</a>";                            
                            
                    $mail->setSubject('Zmiana hasła')
                            ->addTo($email)
                            ->setBodyHtml($html)
                            ->send();
                    setcookie('message','Sprawdz pocztę',0,'/');
                    $this->_redirect('/index');
                }else{
                    setcookie('error-log','Brak maila w bazie',0,'/');
					$this->_redirect('/index');
                }
                
            }
        }
            $this->view->form_new_haslo = $form_new_haslo;
        
    }

    public function zmianahaslaAction()
    {
        $kod = $this->getRequest()->getParam('kod');
        $user = $this->getRequest()->getParam('user');
        $test=null;
        if($kod == hash('sha256',$user.'exrc1234'))
            $test = true;
        else 
            $test = false;
        $form_zmiana_hasla = new Application_Form_Zmianahasla();
        if($test == TRUE){
        $h_user = new Zend_Form_Element_Hidden('user');
        $h_user->setValue($user);
        $h_kod=new Zend_Form_Element_Hidden('kod');
        $h_kod->setValue($kod);
        $form_zmiana_hasla->addElements(array($h_kod,$h_user));
        }
        $form_zmiana_hasla->setAction('/public/logowanie/zmianahasla')
                ->setMethod('post');
        if($test){
            if($this->getRequest()->isPost()){
                if($form_zmiana_hasla->isValid($this->getRequest()->getPost())){
                    $db         =    new Model_DbTable_User();
                    $email      =    $form_zmiana_hasla->getValue('user');
                    $haslo      =    $form_zmiana_hasla->getValue('haslo1');
                    $where      =    $db->getAdapter()->quoteInto('email = ?', $email);
                    $db->update(array('haslo'=>  hash('sha256',$haslo)), $where);
                    $db         =    Zend_Db_Table::getDefaultAdapter();
                    $log        =    new Zend_Auth_Adapter_DbTable($db,'user','email','haslo');
                    $log->setIdentity($email);
                    $log->setCredential(hash('sha256', $haslo));
                    $rezultat   =    $log->authenticate();
                    if($rezultat->isValid()){
                       $sesja   =    Zend_Auth::getInstance();
                       $dane    =    $sesja->getStorage();
                       $dane->write($log->getResultRowObject(array(
                            'id','u_imie','u_nazwisko','email','status'
                            )));
                      if(isset($haslo)) setcookie('message','Hasło zostało zmienione.',0,'/');
                    }                        
                       
                    $this->_redirect('/index');
                    
                }
            }else{
                $this->view->form_zmiana_hasla = $form_zmiana_hasla;
            }
        }
        if(Zend_Auth::getInstance()->hasIdentity()){
            if($this->getRequest()->isPost()){
                if($form_zmiana_hasla->isValid($this->getRequest()->getPost())){
                $db = new Model_DbTable_User();
                $email = Zend_Auth::getInstance()->getIdentity()->email;
                $haslo = $_POST['haslo1'];
                Zend_Auth::getInstance()->clearIdentity();
                Zend_Session::forgetMe();
                $where = $db->getAdapter()->quoteInto('email = ?', $email);
                $db->update(array('haslo'=>  hash('sha256', $haslo)), $where);
                $db2         =    Zend_Db_Table::getDefaultAdapter();
                $log2        =    new Zend_Auth_Adapter_DbTable($db2,'user','email','haslo');
                $log2->setIdentity($email);
                $log2->setCredential(hash('sha256', $haslo));
                $rezultat   =    $log2->authenticate();
                if($rezultat->isValid()){
                   $sesja2   =    Zend_Auth::getInstance();
                   $dane2    =    $sesja2->getStorage();
                   $dane2->write($log2->getResultRowObject(array(
                        'id','u_imie','u_nazwisko','email','status'
                        )));
                   if(isset($haslo)) setcookie('message','Hasło zostało zmienione.',0,'/');
                }                        
                   
                $this->_redirect('/index');
                }
            }else{
                $this->view->form_zmiana_hasla = $form_zmiana_hasla;
            }
        }
//        if(!($test || Zend_Auth::getInstance()->hasIdentity())) $this->_redirect('/');
  
        
    }
    
    

    public function potwierdzenieAction()
    {
       $this->_helper->viewRenderer->setNoRender();
		if(Zend_Auth::getInstance()->hasIdentity()){
		Zend_Auth::getInstance()->clearIdentity();
        Zend_Session::forgetMe();
		}
       
	   $up = new Model_DbTable_User();
        $kod = $this->getRequest()->getParam('kod');
		//echo $kod;
        $user = $this->getRequest()->getParam('user');
		//echo $user;
        if($up->getid($user)){
            if($up->getStatus($user) == 'nowy'){
                if($kod == hash('sha256', $user.'exrc12'))
                {
                    $where = $up->getAdapter()->quoteInto('email = ?', $user);
                    if($up->update(array('status'=>'user'), $where)){
                        setcookie('message','Dziękujemy za potwierdzenie rejestracji.',0,'/');
						$this->_redirect('/index');
                    }
                }
                else
                {
                    setcookie('error-log','Błądny kod.',0,'/');
					$this->_redirect('/index');
                }    
            }
            else {
                setcookie('message','Jesteś już zarejestrowany.',0,'/');
				$this->_redirect('/index');
            }
        }
        else {
            setcookie('error-log','Brak użytkownika o emailu: '.$user,0,'/');
			$this->_redirect('/index');
        }
         
    }


}













