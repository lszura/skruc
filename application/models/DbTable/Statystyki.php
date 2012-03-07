<?php

class Model_DbTable_Statystyki extends Zend_Db_Table_Abstract
{

    protected $_name = 'statystyki';
    protected $_primary = 'id';
    
    public function putdane($id,$ip){
        $this->insert(array(
            'id_link' => $id,
            'ip' => $ip
            
        ));
    }
    public function del($id_link){
        $this->delete('id_link ='.$id_link);
    }
    public function countall($id_link){
        return count($this->fetchAll('id_link = '.$id_link));
    }
    public function countuq($id_link){
        $select = $this->select()->where('id_link = ?',$id_link)
                ->group('ip');
        return count($this->fetchAll($select));
    }
    public function mount($id,$poczatek,$koniec){
        $select1    = $this->select()->where('id_link = ?',$id)
                            ->where('czas >= ?',$poczatek)
                            ->where('czas <= ?',$koniec);
        $select2    = $this->select()->where('id_link = ?',$id)
                            ->where('czas >= ?',$poczatek)
                            ->where('czas <= ?',$koniec)
                            ->group('ip');
        return array('all'  => count($this->fetchAll($select1)),
                     'unique' => count($this->fetchAll($select2)));
    }

}

