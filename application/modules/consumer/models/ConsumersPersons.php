<?php
/*

Table: consumers_coordinators
Columns:
consumers_id	int(11) PK
people_id	varchar(45)

*/
class Consumer_Model_ConsumersPersons extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_persons';
    protected $_primary = 'consumer_id';
    protected $_referenceMap = array(
                              'ConsumerUser' => array(
                              'columns' => array('consumer_id'),
                              'refTableClass'=>'Consumer_Model_Consumer',
                              'refColumns' => array('id')
                             ),
        
                              'CoordinatorsPersons' => array(
                              'columns'=>array('person_id'),
                              'refTableClass'=>'Default_Model_People',
                              'refColumns' => array('id')
                             ));
    
    public function assign($consumer_id, $person_id) {
            
        $count = $this->count( $consumer_id, $person_id);
        
        if( $count == 0 ){
            $data = array('consumer_id'=>$consumer_id, 'person_id'=>$person_id);
            return $this->insert($data);
        }
        
        return false;

    }
    
    
    public function remove($consumer_id, $person_id) {
            
        $count = $this->count( $consumer_id, $person_id );
        
        if( $count > 0 ){
            return $this->delete(array('consumer_id = ?' =>
                                       $consumer_id, 'person_id = ?' => $person_id));
        }
        
        return false;
    }
   
   
    public function findById($person_id) {
        $select = $this->select();
        $select->where( 'person_id = ?', $person_id );
        return $this->fetchAll($select);
    }
   
    
   public function count($consumer_id, $person_id) {
   
        $select = $this->select();
        $select->from($this->_name, array("num"=>"COUNT(consumer_id)", "count_id"=>"consumer_id"));
        $select->where("consumer_id = ?", $consumer_id)->where( 'person_id = ?', $person_id );
        $result = $this->fetchRow($select);
         return (int)$result["num"];
   }
                              
      
}
