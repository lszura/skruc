<?php

class Model_DbTable_Link extends Zend_Db_Table_Abstract
{

    protected $_name = 'link';
    protected $_primary = 'id';

    public function addlink($id,$link){
        $this->insert(array(
            'id_user'=>$id,
            'link'=>$link
        ));
    }
    public function getlinkiduser($id_user,$od,$ile){
        $select = $this->select()->from('link',array('id','link'))
                            ->where('id_user = ?',$id_user)
                ->limit($ile,$od);
        return $this->fetchAll($select)->toArray();
    }
    public function isexist($id){
        return $this->find($id)->valid();
    }
    public function getlink($id){
        return $this->fetchRow('id = '.$id)->link;
    }
    public function testlink($id,$link){
      $select = $this->select()->where('id_user = ?',$id)
                          ->where('link = ?',$link);
      return $this->fetchAll($select)->count();
    }
    public function del($id){
        $this->delete('id = '.$id);
    }
    public function getAllLinksUser($id_user){
        $select = $this->select()->from('link','link')
                ->where('id_user = ?',$id_user);
        return $this->fetchAll($select)->toArray();
    }
    public function ilosclinks($id){
        return count($this->fetchAll('id_user = '.$id));
     
    }
}

