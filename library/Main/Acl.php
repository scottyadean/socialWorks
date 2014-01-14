<?php
class Main_Acl extends Zend_Acl {

    public static $roles = array( 'guest', 'user', 'admin');
    
    public $resources = array('default'=> array('index','error', 'auth', 'user'),
                              'consumer'=> array('index',
                                                 'medical',
                                                 'physician',
                                                 'pharmaceutical',
                                                 'coordinator',
                                                 'agency',
                                                 'medication',
                                                 'hospitalized',
                                                 'allergies',
                                                 'insurance',
                                                 'exam',
                                                 'note'),
                              'media'=> array('index'),
                              'tools' => array('index', 'async', 'crud'),
                              'reports' => array('index', 'isp'),
                              'crud'=>array('index')
                             );

    public $guests = array('default-auth',
                           'default-error');

    public $users = array('default-index', 
                          'default-user',                                
                          'consumer-note',
                          'consumer-exam',
                          'consumer-index',
                          'consumer-medical',
                          'consumer-insurance',
                          'consumer-allergies',
                          'consumer-medication',
                          'consumer-physician',
                          'consumer-pharmaceutical',
                          'media-index',
                          'tools-index',
                          'tools-crud',
                          'reports-index',
                          'tools-async');
                          
    public $admins = array('crud-index');
                          
  
    public function __construct() {
        
        $this->_addRolesAndPermissions();
    }
    
    
    public static function Roles($id = 0) {
        $roles = static::$roles;
        
        if( isset( $roles[(int)$id] ) ) {
            return $roles[(int)$id];
        }
        
        return 'guest';
    
    }
    

    protected function _addRolesAndPermissions(){

        //Add a new role called "guest"        
        $this->addRole(new Zend_Acl_Role(static::Roles(0)));

        //Add a role called user, which inherits from guest
        $this->addRole(new Zend_Acl_Role(static::Roles(1) ), static::Roles(0) );

        //Add a role called admins, which inherits from user
        $this->addRole(new Zend_Acl_Role(static::Roles(2)), static::Roles(1) );

        //Add each resource to the are new ACL
        foreach($this->resources as $resource=>$views ) {
            
            foreach( $views as $v ) {
                $this->addResource(new Zend_Acl_Resource("{$resource}-{$v}"));
            }
        
        }
        
        //guests and users have no permissions. 
        $this->deny('guest');

        //admins have all privlages.  
        $this->allow('admin');

        //set what guests are allowed to access
        foreach( $this->guests as $key=>$views ) {
            $this->allow('guest', $views, 'view');
        }
        
        //set what users are allowed to access
        foreach( $this->users as $key=>$views ) {
            $this->allow('user', $views, 'view');
        }
        
        //set what admins are allowed to access
        foreach( $this->admins as $key=>$views ) {
            $this->allow('admin', $views, 'view');
        }
        
    }





}
