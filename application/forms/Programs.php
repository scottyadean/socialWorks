<?php
class Application_Form_Programs extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $formLabel = 'Add';
   public $_peeps;
   
   public function build( $action = "/programs/create",
                          $id = null,
                          $method = "post" ) {
       
       $this->_id = $id;
       
       if(!is_null($this->_id)) {    
            $this->formLabel = 'Update';
       }
       

       $this->setName("programs-form");
       $this->getData();
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('programs', $this->getFields());
       
       if($this->customSubmitBtn == false){
            $this->formElementsFromArray($this->getCustomFields());
       }
       
       $this->createElements();
    }

    public function getData() {

            $people = new Default_Model_People;
            $map = array();
            $director = $people->_index(array('type = ?'=>'program_director'));
            foreach($director as $d) {
                $map[$d['id']] = $d['fname']." ".$d['lname']; 
 
            }
            
            $this->_peeps = $map;   
    }
  
  /**
   *`director_id`, `title`, `description`
   **/
    public function getFields() {

         $fields = array("title" => array('label'=>'Title', 'required'=> true ),
                         "director_id"=>array('label'=>'Contact Person', 'required'=> true, 'type'=>'select', 'multiOptions'=>$this->_peeps), 
                         "description"=>array('label'=>'Description', 'required'=> true ),
                         'email'=>array(),
                         'web_site'=>array('label'=>'Web Site'),
                         'phone'=>array('label'=>'Phone'),
                         'fax'=>array(),
                         'address'=>array(),
                         'city'=>array(),
                         'county'=>array(),
                          'zip'=>array()
                         /*
                         `phone`, `address`, `city`, `county`, `state`, `zip`
                         */
                         
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
                                  'label'=>'Done',
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