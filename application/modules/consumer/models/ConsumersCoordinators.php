<?php
/*

Table: consumers_coordinators
Columns:
consumers_id	int(11) PK
coordinators_id	varchar(45)

*/

class Consumer_Model_ConsumersCoordinators extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_coordinators';
    protected $_primary = 'consumer_id';
    protected $_referenceMap = array(
                              'ConsumerUser' => array(
                              'columns' => array('consumer_id'),
                              'refTableClass'=>'Consumer_Model_Consumer',
                              'refColumns' => array('id')
                             ),
        
                              'CoordinatorsConsumer' => array(
                              'columns'=>array('coordinator_id'),
                              'refTableClass'=>'Default_Model_Coordinator',
                              'refColumns' => array('id')
                             ));
    
    public function assign($consumer_id, $coordinator_id) {
            
       $count = $this->count( $consumer_id, $coordinator_id );
       
       if( $count == 0 ){
        $data = array('consumer_id'=>$consumer_id, 'coordinator_id'=>$coordinator_id);
        return $this->insert($data);
       }

       return false;

    }
    
    
    public function remove($consumer_id, $coordinator_id) {
            
       $count = $this->count( $consumer_id, $coordinator_id );
      
       if( $count > 0 ){
      
           return $this->delete(array('consumer_id = ?' => $consumer_id,
                                      'coordinator_id = ?' => $coordinator_id));
       }

       return false;

    }
   
   
     public function findById($coordinator_id) {
           $select = $this->select();
           $select->where( 'coordinator_id = ?', $coordinator_id );
           return $this->fetchAll($select);
    }
   
    
   public function count($consumer_id, $coordinator_id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(consumer_id)", "count_id"=>"consumer_id"));
       $select->where("consumer_id = ?", $consumer_id)->where( 'coordinator_id = ?', $coordinator_id );
       $result = $this->fetchRow($select);
     
        //return the int count.
        return (int)$result["num"];
   }
                              
      
}
