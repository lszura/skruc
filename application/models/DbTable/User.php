<?php

class Model_DbTable_User extends Zend_Db_Table_Abstract
{

    protected $_name = 'user';
    protected $_primary = 'id';
    
    public function setuser($imie,$nazwisko,$haslo,$email){
        $this->insert(array(
            'u_imie'=>$imie,
            'u_nazwisko'=>$nazwisko,
            'haslo'=>hash('sha256',$haslo),
            'email'=>$email,
            'status'=>'nowy'
        ));
    }
    public function getId($email){
        $select= $this->select()
                ->from('user',array('id'))
                ->where($this->getAdapter()->quoteInto('email = ?', $email))
                ;
        return $this->fetchRow($select)->id;
    }
    public function getStatus($email){
        $select = $this->fetchRow($this->getAdapter()->quoteInto('email = ?', $email));
        return $select->status;
    }
    public function finduser($email){
        if(isset($this->fetchRow($this->getAdapter()->quoteInto('email = ?', $email))->email))
            return true;
        else
            return false;
    }
}

