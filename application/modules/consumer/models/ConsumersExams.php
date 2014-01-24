<?php

/** 
Columns:
* id	int(11) AI PK
* consumer_id	int(11)
* physician_id	int(11)
* type	varchar(50)
* date	datetime
* result	text

*/

class Consumer_Model_ConsumersExams extends Zend_Db_Table_Abstract {
    protected $_name = 'consumers_exams';
    protected $_primary = 'id';    
    protected $_referenceMap = array(
                        'Consumer' => array(
                            'columns' => array('consumer_id'),
                            'refColumn' => 'id',
                            'refTableClass' => 'Consumer_Model_Consumer'));
                   
    public function createExam($data) {
        
        if(isset($data['id'])) {
            unset($data['id']);
        }
        
        return $this->insert($data);
    }
    
    public function readExam($id) {
        return $this->find((int)$id)->current();
    }
    
    public function updateExam($data) {
        $where = array('id = ?' => (int)$data['id']);
        return $this->update($data, $where);
    }
    
    public function deleteExam($id) {     
        return $this->delete(array('id = ?' => (int)$id));
    }
   
    public function findByConsumerId($consumer_id, $where=array(), $ids = false) {
        
          $select = $this->select()->from($this)->where( 'consumer_id = ?', (int)$consumer_id );
          
          
          if( $ids && !is_array($ids) ){
             $ids = explode(",",$ids);
          }
          
          if($ids){
           $select->where('id IN (?)',  $ids);
          }
          
          
          if(is_array($where)) {
            
            foreach($where as $k=>$w) {
                $select->where( "{$k}", $w );
            }
            
          }
          
          
          return $this->fetchAll($select);
    }

    
    public function findByIds($ids, array $where = array()) {
    
      if( $ids && !is_array($ids) ){
             $ids = explode(",",$ids);
        }
    
          $select = $this->select()->from('consumers_exams')
                 ->setIntegrityCheck(false)
                 ->joinLeft(array('p'=>'physicians'),
                                  'p.id = consumers_exams.physician_id', array('p.name', 'p.phone', 'p.address', 'p.city', 'p.state', 'p.zip'))
                 ->where('consumers_exams.id IN (?)',  $ids)
                 ->order('consumers_exams.date DESC');
             
        if( $where && is_array($where) ) {
            
            foreach($where as $k=>$v) {
                $select->where("consumers_exams.{$k}", $v);
            }
        }
        
        return $this->fetchAll($select);
        
        
    }
    
    public function findByPhysicianId($physician_id) {
          $select = $this->select();
          $select->where( 'physician_id = ?', $physician_id );
          return $this->fetchAll($select);
    }
   
    public function findByConsumerAndPhysicianId($consumer_id, $physician_id) {
       $select = $this->select();
       $select->where("consumer_id = ?", $consumer_id)->where( 'physician_id = ?', $physician_id );
       return $this->fetchRow($select); 
    }
    
    
    public function findByConsumerIdAndMapPhysician($consumer_id,
                                                    $month=false,
                                                    $year=false,
                                                    $ids = false
                                                    ) {
        
        $consumer = new Consumer_Model_Consumer;
        $consumerInfo = $consumer->findById($consumer_id);
        $consumerUsers = $consumer->getConsumerUsers();    
        $physicians = $consumer->getConsumerPhysicians();
        
        $m = $month ? $month : date('m');
        $y = $year ? $year : date('Y');
        if($month || $year){
            $exams = $this->findByConsumerId($consumer_id,
                                             array('date LIKE ?'=> date("{$y}-{$m}")."%" ), $ids);
             
         }else{
            $exams = $this->findByConsumerId($consumer_id, false, $ids);
         }
         
         foreach( $physicians as $key=>$p ) {
            $ex = array();
            foreach($exams as $k=>$e) {
                
               if( $e->physician_id == $p['id'] ) {   
                  $ex[] =  $e;                 
               }
            }
            $physicians[$key]['exams'] = $ex;
        }
        return $physicians; 
    }
    
   public function countExam($id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(id)"));
       $select->where("id = ?", $id);
       $result = $this->fetchRow($select);
       //return the int count.
       return (int)$result["num"];
   }
  
}
