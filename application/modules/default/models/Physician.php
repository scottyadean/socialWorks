<?php

class Default_Model_Physician extends Zend_Db_Table_Abstract {

    protected $_name = 'physicians';
    protected $_primary = 'id';

    public function init() {

    }
    
      public function all($asArray=false) {
      
      $select = $this->select();  
        if( $asArray ){
        
            return $this->fetchAll($select)->toArray();
        
        }else{
        
            return $this->fetchAll($select);
        
        }
        
     }

    public function create($data) {

          return $this->insert($data);
          
    }

    
    public function findByPhysicianName($username) {
       $select = $this->select()->where("username = ?", $username);
       return $this->fetchRow($select);
    }


    public function findById($id) {
      return  $this->find($id)->current();
    }


    public function updatePhysician($id, $data) {
        $where = array('id = ?' => (int)$id);
        return $this->update($data, $where);
    }


    public function countByField($field, $val) {
       $select = $this->select()->where("{$field} = ?", $val);
       $select->from($this->_name,'COUNT(id) AS num');
       return $this->fetchRow($select)->num;
    }
    
    
    public function findAssociations($id) {
    
        $assingedToConsumer = new Consumer_Model_ConsumersPhysicians;
        $assingedConsumer = $assingedToConsumer->findById($id)->toArray();
        
        
        $assingedToMedical = new Consumer_Model_ConsumersMedical;
        $assingedMedical = $assingedToMedical->findByColumn('physician_id', $id)->toArray(); 
        
        return array( 'assingedToConsumer' => $assingedConsumer, 'assingedToMedical'=>  $assingedMedical);
        
    }
    
    public function deletePhysician($id) {
         
       $found = $this->find($id)->current()->toArray();
        
       if(count($found) > 0){
            $where = array('id = ?' => (int)$id);
           return  $this->delete($where);
       
       }
    
        return false;
    
    }

}

