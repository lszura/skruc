<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        
    }

    public function indexAction()
    {
        // action body
        $link = new Application_Form_Link();
        $link->setMethod('post');
        $link->setAction('/public/index/dodaj');
        $this->view->link = $link;
        
        
        }

    public function testAction()
    {
        // action body
        if(isset($_COOKIE['error-log'])){
            $this->view->message    =   "
                <script type=\"text/javascript\">
                $(\".alert\").alert()
                </script>
                <div class=\"alert alert-block alert-error fade in\">
                <a class=\"icon-remove-circle close\" data-dismiss=\"alert\" href=\"#\"></a>
                <h4>Wiadomość</h4>
                <p>".$_COOKIE['error-log']."</p>
                </div>
                 ";
            setcookie('error-log','',time()-1000,'/');
        }
        if(isset($_COOKIE['message'])){
            $this->view->message    =   "
                <script type=\"text/javascript\">
                $(\".alert\").alert()
                </script>
                <div class=\"alert alert-block alert-info fade in\">
                <a class=\"icon-remove-circle close\" data-dismiss=\"alert\" href=\"#\"></a>
                <h4>Wiadomość</h4>
                <p>".$_COOKIE['message']."</p>
                </div>
                 ";
            setcookie('message','',time()-1000,'/');
        }
        if(Zend_Auth::getInstance()->hasIdentity())
        if(Zend_Auth::getInstance()->getIdentity()->status == 'nowy')
                {
                $this->view->message    =   "
                <script type=\"text/javascript\">
                $(\".alert\").alert()
                </script>
                <div class=\"alert fade in\">
                <a class=\"icon-remove-circle close\" data-dismiss=\"alert\" href=\"#\"></a>
                <h4>Wiadomość</h4>
                <p>Sprawdz emaila w celu potwierdzenia konta</p>
                </div>
                 ";
                }
                
                
    }

    public function dodajAction()
    {
        // action body
        if(Zend_Auth::getInstance()->hasIdentity()){
        $email      	=	Zend_Auth::getInstance()->getIdentity()->email;
        $id         	=	Zend_Auth::getInstance()->getIdentity()->id;
        $db_link    	=	new Model_DbTable_Link();
        if($this->getRequest()->isPost()){
            $db_link 	=	new Model_DbTable_Link();
                       if($db_link->testlink($id, $_POST['link'])){
							setcookie('message','Link jest już w bazie.',0,'/');
						}else{
							$db_link->addlink($id, $_POST['link']);
							$this->_redirect('/index'); 
						}
        }
        }else{
           $url 		=	$this->_request->getParam('link');
           setcookie('url',$url,0,'/');
           $this->_redirect('/logowanie/nowyuser');
		}
    }

    public function checkemailAction()
    {
        // action body
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
        $email		=	trim(strtolower($_REQUEST['email_u']));
        $regex		=	'/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/';
        $db_user	=	new Model_DbTable_User();
        if(preg_match($regex, $email)&&(!$db_user->finduser($email)))
            echo "true";
        else
            echo "false";
    }

    public function daneAction()
    {
        // action body
        $formszukaj                 =   new Application_Form_Szukaj();
        $this->view->formszukaj     =   $formszukaj;
        $db_link                    =   new Model_DbTable_Link();
        $id                         =   Zend_Auth::getInstance()->getIdentity()->id;
        $ilosc                      =   $db_link->ilosclinks($id);
        $this->view->ilosc          =   $ilosc;
    }
	
	

}









