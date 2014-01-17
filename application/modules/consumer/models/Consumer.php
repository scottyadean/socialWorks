<?php

class Consumer_Model_Consumer extends Zend_Db_Table_Abstract {

    protected $_name = 'consumers';
    protected $_primary = 'id';
    protected $_dependentTables = array( 'Consumer_Model_ConsumersUsers',
                                         'Consumer_Model_ConsumersInsurance',
                                         'Consumer_Model_ConsumersExams',
                                         'Consumer_Model_ConsumersGoals',
                                         'Consumer_Model_ConsumersNotes',
                                         'Default_Model_UsersConsumers',
                                         'Default_Model_Coordinator',
                                         'Default_Model_User',
                                         'Default_Moodel_Physician');
    
    protected $_current;
    
    public function init() {
        
    }

    public function getTableName() {
        
        return $this->_name; 
    }

    public function getConsumerUsers() {
    
        return $this->_current->findManyToManyRowset(
                    'Default_Model_User',
                    'Consumer_Model_ConsumersUsers')->toArray();
    }
                 
    public function getConsumerPhysicians(){   
    
        return $this->_current->findManyToManyRowset(
                   'Default_Model_Physician',
                   'Consumer_Model_ConsumersPhysicians')->toArray();
    }
    
   public function  getConsumerPharmaceuticals(){   
    
        return $this->_current->findManyToManyRowset(
                   'Default_Model_Pharmaceutical',
                   'Consumer_Model_ConsumersPharmaceuticals')->toArray();
    }
    
    
    public function getConsumerCoordinators() {
        return $this->_current->findManyToManyRowset(
                   'Default_Model_Coordinator',
                   'Consumer_Model_ConsumersCoordinators')->toArray();    
    }
    
    public function getConsumerInsurance(){
        return $this->_current->findDependentRowset(
               'Consumer_Model_ConsumersInsurance')->toArray();
    }
    
    public function getConsumerExams(){
        
        return $this->_current->findDependentRowset(
               'Consumer_Model_ConsumersExams')->toArray();
        
    }

    public function getConsumerGoals(){
        
        return $this->_current->findDependentRowset(
               'Consumer_Model_ConsumersGoals')->toArray();
    }
    
    public function getConsumerAppointments(){

        return $this->_current->findDependentRowset(
               'Consumer_Model_ConsumersMedical')->toArray();
    }

    
    
    
    public function getConsumerNotes(){
        
        return $this->_current->findDependentRowset(
               'Consumer_Model_ConsumersNotes')-toArray();

    }    



    public function create($params) {
        
        /*
            get current sql date
        */
        $date = new Zend_Db_Expr('NOW()');
        $data = $params;
        $data['create_date'] = $date;
        
        return $this->insert($data); 
    }


    public function findAll() {
       $select = $this->select();
       return $this->fetchAll($select);
    }
    
    
    public function findByIds(array $ids) {
       $ids = implode(",", $ids);
       $select = $this->select()->where("id IN ({$ids})");
       $res = $this->fetchAll($select);
       return $res;
    }
    
    public function findById($id) {
       $this->_current = $this->find($id)->current();
       return $this->_current;
    }


    
    public function findByName($fname = '', $lname = false) {    
       $select = $this->select()->where("fname = %?%", $fname);
       
       if( isset( $lname )  && $lname != false) {
           $select->where("lname = %?%", $lname);
       }
             
       return $this->fetchRow($select);
    }

    public function updateConsumer($data, $id) {
        $where = array('id = ?' => (int)$id);
        return parent::update($data, $where) ? true : false;
    }


    public function countByField($field, $val) {
       $select = $this->select()->where("{$field} = ?", $val);
       $select->from($this->_name,'COUNT(id) AS num');
       return $this->fetchRow($select)->num;
    }

}
