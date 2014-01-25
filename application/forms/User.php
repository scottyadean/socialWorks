<?php
class Application_Form_User extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $formLabel = 'Update';
   public $_types;
   
   public function build( $action = "/account/edit",
                          $id = null,
                          $method = "post" ) {
       
       $this->_id = $id;
       
       $this->setName("user-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('users', $this->getFields());
       
       if($this->customSubmitBtn == false){
            $this->formElementsFromArray($this->getCustomFields());
       }
       
       $this->createElements();
    }

    

  /**
   *`username`, `password`, `salt`, `fname`, `lname`, `phone`, `email`, `status`, `position`, `role`, `last_log`, `date_created`
   **/
    public function getFields() {

         $fields = array("fname"=>array('label'=>'First Name', 'required'=> true), 
                         "lname"=>array('label'=>'Last Name', 'required'=> true ),
                         "email"=>array('label'=>'Email', 'required'=> false ),
                         "phone"=>array('label'=>'Phone', 'required'=> true),
                         "cell"=>array('label'=>'Cell', 'required'=> false),
                         "address"=>array('label'=>'Address', 'required'=> false),
                         "city"=>array('label'=>'City', 'required'=> false),
                         "state"=>array('label'=>'State', 'required'=> false),
                         "zip"=>array('label'=>'Zip', 'required'=> false),
                         "bio"=>array('label'=>'Bio','required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8'))
                         
                         );
        
           if( isset( $this->_id ) ) {
              
              $fields['id'] = array('default'=>$this->_id, 'type'=>'hidden', 'required'=> true,
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'));       
           }
           
           return $fields;
    }


    public function getCustomFields() {
    $custom = array('submit' => array(
                                 'label'=>$this->formLabel,
                                 'type'=>'submit',
                                 'name'=>'submit',
                                 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'), 
                                 'options' => array('class'=>'btn btn-small btn-primary'),
                                 'ignore'=>true),
                   'cancel' => array(
                                  'label'=>'Cancel',
                                  'type'=>'button',
                                  'name'=>'cancel',
                                  'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                                  'attributes'=>array('onclick'=>"window.history.back()"),
                                  'options' => array('class'=>'btn btn-small btn-primary'),
                                  'ignore'=>true       
                                 ));
    return $custom;
    
    }   
}