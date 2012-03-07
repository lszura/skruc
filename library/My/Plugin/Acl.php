<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of My_Plugin_Acl
 *
 * @author luk
 */
class My_Plugin_Acl extends Zend_Controller_Plugin_Abstract{
    //put your code here

    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        parent::dispatchLoopStartup($request);
        
       $acl = new Zend_Acl();
       
       $acl->addRole(new Zend_Acl_Role('guest'));
       $acl->addRole(new Zend_Acl_Role('nowy'),'guest');
       $acl->addRole(new Zend_Acl_Role('user'));
       
       
       $acl->add(new Zend_Acl_Resource('index'));
       $acl->add(new Zend_Acl_Resource('logowanie'));
       $acl->add(new Zend_Acl_Resource('jsondane'));
       $acl->add(new Zend_Acl_Resource('error'));
       $acl->allow('guest','index',array('index','dodaj','test','checkemail'));
       $acl->allow('guest','logowanie',array('zmianahasla','zaloguj','potwierdzenie','nowyuser','nowehaslo'));
       $acl->allow('guest','error','error');
       $acl->allow('nowy','logowanie','wyloguj');
       $acl->allow('nowy','index','dane');
       $acl->allow('user',null);
       //var_dump(Zend_Auth::getInstance()->getIdentity());
       if(Zend_Auth::getInstance()->hasIdentity()){
       $role = Zend_Auth::getInstance()->getIdentity()->status; 
       //echo Zend_Auth::getInstance()->getIdentity()->email;
       }else{
       $role = 'guest';    
       }
       if (!$acl->isAllowed($role,
                             $request->getControllerName(),
                             $request->getActionName())
        ){
         $request->setControllerName('index');
        $request->setActionName('index'); 
        return;
       }
       
//       $auth = Zend_Auth::getInstanse();
/*    
    
    $controller = $request->getControllerName();
    $action = $request->getActionName();
    if (!$acl->isAllowed($role, $controller, $action)) {
      if ($role == 'guest') {
        $request->setControllerName('index');
        $request->setActionName('index');
      } else {
        $request->setControllerName('error');
        $request->setActionName('brak-dostepu');
      }
    }

  * 
  */       
    }
}

?>
