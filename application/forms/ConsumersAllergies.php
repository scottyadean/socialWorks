<?php
class Application_Form_ConsumersAllergies extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $formType = 'Add';
   public $insuranceTypes = array();
   
   public function build( $action = "/consumer/allergies/new/",
                          $id = null,
                          $method = "post" ) {
       
       $this->_id = $id;
       
       if(!is_null($this->_id)) {    
            $this->formType = 'Update';
       }
       
       $this->setName("consumer-allergies-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('consumers_allergies', $this->getFields());
       
       if($this->customSubmitBtn == false){
            $this->formElementsFromArray($this->getCustomFields());
       }
       
       $this->createElements();
    }

  /**
    `id`,
    `consumer_id`,
    `allergy`,
    `info`
   */
    public function getFields() {

         $fields = array("consumer_id" => array('required'=> true,  'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                         "allergy" => array('label'=>'Allergy', 'required'=> true),
                         "info"=>array('label'=>'Info','required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8')));
        
           if( !empty( $this->_id ) ) {
              
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