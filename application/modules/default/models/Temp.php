<?php

/**
*/
class Default_Model_Temp {

    //Table name 
    public static $_name = 'temp';
    
    //Tables primary key.
    public static $_primary = 'id';

    //The zend db adaptor object.
    public static $_db = null;

    //cap limit number of records to hold
    public static $_cap = 1000;
    
    //truncate number of files to remove when cap is rearched
    public static $_truncate = 10;

    /** 
    * Returns the table name. 
    * @return<string>
    */   
    public static function getTable() {
        return self::$_name;
    }

    /** 
    * save or update. 
    * @return<string>
    */ 
    public static function saveTempData() {
    
        if(self::countTemp($data['pointer']) == 0) {
        
            self::insertTemp($data);
            
        }else{
        
            self::updateTemp($data);
        }
    
    }
    
    public static function insertTemp($data) {
    
       //if count > cap amount remove last row.
        if(self::countAll() > self::$_cap){
            self::removeTemp();
        }
        

        $data['created'] = time();
    
       $db = self::_getDB();  
       return $db->insert(self::getTable(), $data);
    
    }
    
    
    public static function updateTemp($data) {
    
       //if count > cap amount remove last row.
        if(self::countAll() > self::$_cap){     
            self::removeTemp();
        }
    
        $db = self::_getDB();
       return $db->update(self::getTable(), $data, array('pointer = ?' => $data['pointer']));
    
    }    

    public static function selectAll($limit = 100) {

        $db = self::_getDB();
        $select = $db->select()->from(self::getTable())->limit($limit);
        
        return $db->fetchAll($select);
    }
    
    public static function selectAllByAccountId( $account_id, $offsetId = 1 ) {
        
        $db = self::_getDB();
        
        $select = $db->select()->from(self::getTable())
                               ->where( "account_id = ?", $account_id )
                               ->where( "id > ?", $offsetId )
                               ->order(array('DESC' => 'id'));
        
        return $db->fetchAll($select);
        
    }
    
    
    public static function selectByPointer($pointer) {

        $db = self::_getDB();
        $select = $db->select()
                     ->where("pointer = ?", $val)
                     ->from(self::getTable());

        return $db->fetchRow($select);
    }

    /** 
    * count
    * return a count of rows with plan_id = to current plan
    * @param $account_id<int> account id
    * @return<int>
    */
    public static function countTemp($account_id) {

        $db = self::_getDB();
        
        $select = $db->select()
        ->from(self::getTable(), array("num"=>"COUNT(id)"))
        ->where("account_id = ?", $account_id);
        
        $result = $db->fetchRow($select);
        
        return (int)$result["num"];

    }
    
    
    public static function removeTemp(){
    
        $db   = self::_getDB();
        $table = self::getTable();
        $sql  = "DELETE FROM {$table} ORDER BY id ASC LIMIT {$this->_truncate}";
        $stmt = $db->query($sql);
        return $stmt->execute();
    }
    
    
        /** 
    * count
    * return a count of rows with plan_id = to current plan
    * @param $pointer<array> impactopt plan id
    * @return<int>
    */
    public static function countAll() {

        $db = self::_getDB();
        
         $select = $db->select()
        ->from(self::getTable(), array("id"))
        ->where("id != ?", "NULL");
        
        return count($db->fetchAll($select));
 
    }
    
    /**
    * set the static db var with a instance of the default adapter.
    * @return<object>
    */
    public static function _getDB() {
    
        if(is_null(self::$_db))
            self::$_db = Zend_Db_Table::getDefaultAdapter();
            
        return self::$_db;      
    
    }
    
    

}
