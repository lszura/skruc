<?php

class JsondaneController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->getHelper('layout')->disableLayout();
    }

    public function indexAction()
    {
       
        
    }

    public function getlinksAction()
    {
        // action body
        $db_link        =   new Model_DbTable_Link();
        $id             =   Zend_Auth::getInstance()->getIdentity()->id;
        $kod            =   new Application_Model_Kodowanie();
        $tab            =   array();
        $i              =   1;
        $od             =   $_REQUEST['lod'];
        $ile            =  $_REQUEST['lile'];
        foreach ($db_link->getlinkiduser($id,$od,$ile) as $dane){
            $tab[$i-1]['id']          =   $i+$od;
            $tab[$i-1]['url']		  =	  $_SERVER['SERVER_NAME'].'/public/'.$kod->na62($dane['id']);
            $tab[$i-1]['link']        =   $dane['link'];
            $i++;
        }
        echo Zend_Json_Encoder::encode($tab);
     }
     public function dellinkAction(){
        $db_link        =   new Model_DbTable_Link();
        $db_stat        =   new Model_DbTable_Statystyki();
        $kod            =   new Application_Model_Kodowanie();
        $tmp            =   explode('/', $_REQUEST['id']);
        $id             =   $kod->z62($tmp[2]);
        if($db_link->isexist($id)){
            $db_link->del($id);
            $db_stat->del($id);
        }
        
     }
     public function searchlinkAction(){
         $id_user       =   Zend_Auth::getInstance()->getIdentity()->id;
         $db_link       =   new Model_DbTable_Link();
         $data          =   null;
         $kod           =   new Application_Model_Kodowanie();
         if(isset($_REQUEST['data']))
         $data		    =   $_REQUEST['data'];
         $tmp_array     =   $db_link->getlinkiduser($id_user,0,0);
         $tmp_array_s   =   array();
         $i             =   1;
         foreach ($tmp_array as $tmp){
             if (stristr($tmp['link'], $data)){
                 $a      =   array(
                     'id'   => $i,
                     'url'  => $_SERVER['SERVER_NAME'].'/public/'.$kod->na62($tmp['id']),
                     'link' => $tmp['link']
					);
                 array_push ($tmp_array_s, $a);
                 $i++;
            }
        }
        echo Zend_Json_Encoder::encode($tmp_array_s);
    }
     
     public function countAction(){
         $tmp1      =   explode('/', $_REQUEST['id']);
         $kod       =   new Application_Model_Kodowanie();
         $id        =   $kod->z62($tmp1[2]);
         $db_stat   =   new Model_DbTable_Statystyki();
         $tmp       =   array(
             'countall' => $db_stat->countall($id),
             'countuq'  => $db_stat->countuq($id)
         );
         echo Zend_Json_Encoder::encode($tmp);
         
     }
     public function mountAction(){
         $m         =   (int)date('m');
         $tmp1      =   explode('/', $_REQUEST['id']);
         $kod       =   new Application_Model_Kodowanie();
         $id        =   $kod->z62($tmp1[2]);
         $db_stat   =   new Model_DbTable_Statystyki();
         $tmp       =   array();
         for($i=0;$i<$m;$i++){
         $poczatek  =   date("Y-m-d H:i:s",  mktime(0,0,0,date($i+1),01,  date('Y')));
         $dni       =   date('t',mktime(0,0,0,date($i+1),01,  date('Y')));
         $koniec    =   date("Y-m-d H:i:s",  mktime(23,59,59,date($i+1),$dni,  date('Y')));
         array_push($tmp, $db_stat->mount($id,$poczatek,$koniec));
         }

         $all;
         $uq;
         for($i=0;$i<12;$i++){
            if(isset($tmp[$i]['all']))
                $all[$i] = $tmp[$i]['all'];
			if(isset($tmp[$i]['unique']))
                $uq[$i] = $tmp[$i]['unique'];
         }  
         echo Zend_Json_Encoder::encode(array('all'=>$all,'unique'=>$uq));
     }
}



