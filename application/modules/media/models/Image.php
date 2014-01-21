<?php

class Media_Model_Image extends Zend_Db_Table_Abstract {
    //`relation_id`, `img`, `type`, `ext`
    protected $_name = 'images';
    protected $_primary = 'relation_id';

    
    
    public function init() {

    }


    public function create( $id, $type, $blob, $ext, $overwrite = false ) {
   
            $data = array( 'relation_id' => $id,
                           'img' => base64_encode($blob),
                           'type'=> $type,
                           'ext'=>$ext
                         );
   
   
           if(  $this->countByIdAndType($id, $type) == 0 ){  
                $this->insert($data);
           }
           
           
           return $this->updateImage($id, $type, $data);
           
           
            
    }
    
    public function updateImage($id, $type, $data) {
        $where = array('relation_id = ?' => $id, 'type = ?'=> $type);
        return $this->update($data, $where);
    }

    
    
    public function findbyIdAndType($id, $type) {
    
       $select = $this->select()->where("relation_id = ?", $id)->where( 'type = ?', $type );
       return $this->fetchRow($select);

    }


    public function countByIdAndType($id, $type) {
       $select = $this->select()->where("relation_id = ?", $id)->where("type = ?", $type);
       $select->from($this->_name,'COUNT(relation_id) AS num');
       return $this->fetchRow($select)->num;
    }


    public function countByField($field, $val) {
       $select = $this->select()->where("{$field} = ?", $val);
       $select->from($this->_name,'COUNT(relation_id) AS num');
       return $this->fetchRow($select)->num;
    }
    
    
}