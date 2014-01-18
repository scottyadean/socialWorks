<?php
class Application_Form_ConsumerExams extends Main_Forms_Builder {

   public $_id;
   public $customSubmitBtn = false;
   public $physicians;
   public $consumerId;
   public $formType = 'Add';
    
    public function build( $action = "/consumer/exams/new/",
                           $id = null,
                           $consumer_id = null,
                           $method= "post" ) {
       
       $this->_id = $id;
       $this->consumerId = $consumer_id;
       
       $this->setName("consumer-exam-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->getData();
       $this->formElementsFromTable('consumers_exams', $this->getFields());
       
       if($this->customSubmitBtn == false){
         $this->formElementsFromArray($this->getCustomFields());
       }
       
       $this->createElements();
    }

    public function getData() {
        
        $consumer = new Consumer_Model_Consumer;
        $consumerInfo = $consumer->findById($this->consumerId);
        $consumerUsers = $consumer->getConsumerUsers();
        $consumerPhysicians = $consumer->getConsumerPhysicians();
        
        foreach( $consumerPhysicians as $dr ) {
            $this->physicians[$dr['id']] = $dr['name'];
        }
        
    }
  
  /**
    * id int(11) AI PK
    * consumer_id int(11)
    * physician_id int(11)
    * type int(11)
    * date datetime
    * result text
    */
    public function getFields() {

         $fields = array("consumer_id" => array('default'=>$this->consumerId, 'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                         "physician_id" => array('label'=>'Physician', 'type'=>'select', 'multiOptions'=>$this->physicians ),
                         "type"=>array('label'=>'Exam Type'), 
                         "date"=>array('label'=>'Date of Last Exam', 'attributes'=>array('class'=>'date_widget') ),
                         "result"=>array('required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8')));
        
           if( !empty( $this->_id ) ) {
              
              $fields['id'] = array('default'=>$this->_id, 'type'=>'hidden',
                                    'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'));       
           }
           
           return $fields;
   
    }


    public function getCustomFields() {
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
