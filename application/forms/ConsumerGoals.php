<?php
class Application_Form_ConsumerGoals extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $formType = 'Add';
   public $insuranceTypes = array();
   
   public function build( $action = "/goals/create/",
                          $id = null,
                          $method = "post" ) {
       
       $this->_id = $id;
       
       if(!is_null($this->_id)) {    
            $this->formType = 'Update';
       }
       
       $this->setName("consumer-goals-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('consumers_goals', $this->getFields());
       
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
                         "goal" => array('label'=>'Goal Info', 'required'=> true, 'attributes'=>array('rows'=>'4', 'cols'=>'8', 'class'=>'textarea-standard-size')),
                         "summary" => array('label'=>'Summary', 'required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8', 'class'=>'textarea-standard-size')),
                         "objective" => array('label'=>'Objective', 'required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8', 'class'=>'textarea-standard-size')),
                         "effective_start_date" => array('attributes'=> array('class'=>'date_widget')),
                         "effective_complete_date" => array('attributes'=> array('class'=>'date_widget')),
                         "achieved"=>array('label'=>'Achieved', 'type'=>'select', 'default'=>'N', 'multiOptions'=>array('N'=>'No','Y'=>'Yes'), 'required'=> false));
        
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