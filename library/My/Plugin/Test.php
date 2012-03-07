<?php


class My_Plugin_Test 
    extends Zend_Controller_Plugin_Abstract {
    //put your code here
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
        parent::dispatchLoopStartup($request);
        
        
        $url = $_SERVER['REQUEST_URI'];
        if(substr_count($url, '/')==2){
            $url =  str_replace('/public/', '', $_SERVER['REQUEST_URI']);
			if(!preg_match('/[!@#$%^&*()\-+_{}|:";\',.\/<>?\[\]\=]/', $url)){
            $kod = new Application_Model_Kodowanie();
            $id = $kod->z62($url);
            $db_link = new Model_DbTable_Link();
			
            if ($db_link->isexist($id)){
                $db_s = new Model_DbTable_Statystyki();
                $ip   = $_SERVER['REMOTE_ADDR'];
                $db_s->putdane($id, $ip);
                $link = $db_link->getlink($id);
				echo "<script> window.location = \"$link\" </script>";
                
            }
            
            
            
            
            }
        }
    }
}

?>
