<?php
class Consumer_Model_ConsumersPhysicians extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_physicians';
    protected $_primary = 'consumer_id';
    protected $_referenceMap = array(
                              'ConsumerUser' => array(
                              'columns' => array('consumer_id'),
                              'refTableClass'=>'Consumer_Model_Consumer',
                              'refColumns' => array('id')
                             ),
        
                              'PhysicianConsumer' => array(
                              'columns'=>array('physician_id'),
                              'refTableClass'=>'Default_Model_Physician',
                              'refColumns' => array('id')
                             ));
                             
                             
     
    
    public function assign($consumer_id, $physician_id) {
            
       $count = $this->count( $consumer_id, $physician_id );
       
       if( $count == 0 ){
        $data = array('consumer_id'=>$consumer_id, 'physician_id'=>$physician_id);
        return $this->insert($data);
       }

       return false;

    }
    
    
    public function remove($consumer_id, $physician_id) {
            
       $count = $this->count( $consumer_id, $physician_id );
      
       if( $count > 0 ){
      
           return $this->delete(array('consumer_id = ?' => $consumer_id, 'physician_id = ?' =>$physician_id));
       }

       return false;

    }
   
   
     public function findById($physician_id) {
           $select = $this->select();
           $select->where( 'physician_id = ?', $physician_id );
           return $this->fetchAll($select);
    }
   
    
   public function count($consumer_id, $physician_id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(consumer_id)", "count_id"=>"consumer_id"));
       $select->where("consumer_id = ?", $consumer_id)->where( 'physician_id = ?', $physician_id );
       $result = $this->fetchRow($select);
     
        //return the int count.
        return (int)$result["num"];
      
   }
                              
      
}
