<?php
class Application_Form_ConsumerPrograms extends Main_Forms_Builder {

   public $formLabel = 'Add';
   public $customSubmitBtn = false;
   public $_programs;
   public $_id;
   public $_consumer_id;

   public function build( $action = "/new",
                          $id = null,
                          $_consumer_id = 0,
                          $method = "post" ) {

      $this->_id = $id;
      $this->_consumer_id = $_consumer_id;
      
       if ( !empty( $this->_id )) {
            $this->formLabel = 'Update';
       }

       $this->setName("consumersprogramsform");
       $this->getData();
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('consumers_programs', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();

    }

    public function getFields() {
       $fields =  array("consumer_id" => array('default'=>$this->_consumer_id, 'value'=>$this->_consumer_id, 'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                        "program_id" => array( 'label'=>'Program', 'type'=>'select', 'multiOptions'=>$this->_programs ),
                        "dates_attended" => array('label'=>'Dates Attended'),
                        "program_info" => array('label'=>'Info', 'required'=> false, 'attributes'=>array( 'class'=>'textarea-standard-size' )));
       
        if(!empty($this->_id )) {
        
            $fields['id'] = array('type'=>'hidden',
            'name'=>'id',
            'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
            'default'=>$this->_id,
            'required'=>true);
        }

       
        return $fields;
    }

   public function getData() {
        
        $programs = new Default_Model_Crud;
        $programs->setDbName('programs');
        
        $_programs = $programs->_index(array())->toArray();
        
        foreach( $_programs as $p ) {
            $this->_programs[$p['id']] = $p['title'];
        }

        
    }
    
    public function getCustomFields() {
     
      if($this->customSubmitBtn){
 
          $custom = array(); 
       
      }else{
       
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
                                    'attributes'=>array('onclick'=>"history.back();"),
                                    'options' => array('class'=>'btn btn-small btn-primary'),
                                    'ignore'=>true       
                                   ));
                                   
       }                        
         
     return $custom;
    }    
}
