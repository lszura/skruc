<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initAutoload()
    {
            $moduleLoader = new Zend_Application_Module_Autoloader(array(
                        'namespace' => '',
                        'basePath' => APPLICATION_PATH));

                $autoloader = Zend_Loader_Autoloader::getInstance();
                $autoloader->registerNamespace(array('My_'));
                return $moduleLoader;
    //    $autoloader = Zend_Loader_Autoloader::getInstance();
    //    $autoloader->registerNamespace('');
    //    $autoloader->suppressNotFoundWarnings(true);
    }
  
    public function _initDbNames(){
         try {
                 if ($this->hasPluginResource('db')){
                        $db = $this->getPluginResource('db');
                        $db->getDbAdapter()->query('SET NAMES UTF8');
                 }
         }catch (Exception $e) {
              echo "Blad polaczenia z baza danych: ".$e->getMessage() . PHP_EOL;
              exit(0);
         }}
  protected function _initControllerPlugins(){
  $frontController = Zend_Controller_Front::getInstance();
  $frontController->registerPlugin(new My_Plugin_Acl());
  $frontController->registerPlugin(new My_Plugin_Test());
  

 
  }
 


}

