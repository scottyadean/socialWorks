<?php
class Consumer_Model_ConsumersUsers extends Zend_Db_Table_Abstract 
{
    protected $_name = 'consumers_users';
    protected $_primary = 'consumer_id';
    protected $_referenceMap = array(
                              'ConsumerUser' => array(
                              'columns' => array('consumer_id'),
                              'refTableClass'=>'Consumer_Model_Consumer',
                              'refColumns' => array('id')
                             ),        
                              'UserConsumer' => array(
                              'columns'=>array('user_id'),
                              'refTableClass'=>'Default_Model_User',
                              'refColumns' => array('id')
                             ));
                             
                             
   
    
     
    
    public function assign($consumer_id, $user_id) {
            
       $count = $this->count( $consumer_id, $user_id );
       
       if( $count == 0 ){
        $data = array('consumer_id'=>$consumer_id, 'user_id'=>$user_id);
        return $this->insert($data);
       }

       return false;

    }
    
    
    
    public function remove($consumer_id, $user_id) {
            
       $count = $this->count( $consumer_id, $user_id );
      
       if( $count > 0 ){
      
           return $this->delete(array('consumer_id = ?' => $consumer_id, 'user_id = ?' =>$user_id));
       }

       return false;

    }
   
    
   public function count($consumer_id, $user_id) {
   
       $select = $this->select();
       $select->from($this->_name, array("num"=>"COUNT(consumer_id)", "count_id"=>"consumer_id"));
       $select->where("consumer_id = ?", $consumer_id)->where( 'user_id = ?', $user_id );
       $result = $this->fetchRow($select);
     
        //return the int count.
        return (int)$result["num"];
      
   }
   
    public function getByUserId($user_id) {
   
        $result_ids = $this->fetchAll($this->select()->where( 'user_id = ?', $user_id ))->toArray();
     
        if( count($result_ids) == 0 ) {
            
            return null;
            
        }
        
        $consumer_ids = array();
        foreach( $result_ids as $val){
            
            $consumer_ids[] = $val['consumer_id'];
            
        }
        
   
        $consumerModel = new Consumer_Model_Consumer;
        
        
        
        return  $consumerModel->findByIds($consumer_ids);
   
  }
 
   
                              
      
}
