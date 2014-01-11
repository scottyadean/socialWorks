<?php
class Consumer_Model_ConsumersPharmaceuticals extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_pharmaceuticals';
    protected $_primary = 'consumer_id';
    protected $_referenceMap = array(
                              'ConsumerUser' => array(
                              'columns' => array('consumer_id'),
                              'refTableClass'=>'Consumer_Model_Consumer',
                              'refColumns' => array('id')
                             ),
        
                              'PharmaceuticalConsumer' => array(
                              'columns'=>array('pharmaceutical_id'),
                              'refTableClass'=>'Default_Model_Pharmaceutical',
                              'refColumns' => array('id')
                             ));
                             
                             
     
    
    public function assign($consumer_id, $pharmaceutical_id) {
            
       $count = $this->count( $consumer_id, $pharmaceutical_id );
       
       if( $count == 0 ){
        $data = array('consumer_id'=>$consumer_id, 'pharmaceutical_id'=>$pharmaceutical_id);
        return $this->insert($data);
       }

       return false;

    }
    
    
    public function remove($consumer_id, $pharmaceutical_id) {
            
       $count = $this->count( $consumer_id, $pharmaceutical_id );
      
       if( $count > 0 ){
      
           return $this->delete(array('consumer_id = ?' => $consumer_id, 'pharmaceutical_id = ?' =>$pharmaceutical_id));
       }

       return false;

    }
   
   
     public function findById($pharmaceutical_id) {
     
           $select = $this->select();
           $select->where( 'pharmaceutical_id = ?', $pharmaceutical_id );
    
           return $this->fetchAll($select);
    
    }
   
    
   public function count($consumer_id, $pharmaceutical_id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(consumer_id)", "count_id"=>"consumer_id"));
       $select->where("consumer_id = ?", $consumer_id)->where( 'pharmaceutical_id = ?', $pharmaceutical_id );
       $result = $this->fetchRow($select);
     
       //return the int count.
       return (int)$result["num"];
      
   }
                              
      
}
