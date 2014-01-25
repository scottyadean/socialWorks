<?php
class Application_Form_ConsumersHospitalized extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $formType = 'Add';
   public $insuranceTypes = array();
   
   public function build( $action = "/hospitalized/create/",
                          $id = null,
                          $method = "post" ) {
       
       $this->_id = $id;
       
       if(!is_null($this->_id)) {    
            $this->formType = 'Update';
       }
       
       $this->setName("consumer-hospitalized-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('consumers_hospitalized', $this->getFields());
       
       if($this->customSubmitBtn == false){
            $this->formElementsFromArray($this->getCustomFields());
       }
       
       $this->createElements();
    }

  /**
    `consumer_id`, `hospital`, `reason`, `duration_of_stay`
   */
    public function getFields() {

         $fields = array("consumer_id" => array('required'=> true,  'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                         "hospital" => array('label'=>'Hospital Name', 'required'=> true),
                         "duration_of_stay" => array('label'=>'Duration of stay', 'required'=> true),
                         "date" => array('label'=>'Date', 'required'=> true, 'attributes'=>array('class'=>'date_widget') ),
                         "reason"=>array('label'=>'Reason','required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8')));
        
           if( !empty( $this->_id )  && (int)$this->_id != 0) {
              
              $fields['id'] = array('default'=>$this->_id, 'type'=>'hidden', 'required'=> true,
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'));       
           }
           
           return $fields;
    }


    public function getCustomFields() {
        
    if($this->customSubmitBtn) {
     return array();
    }
    $custom = array('submit' => array(
                                 'label'=>$this->formType,
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