<?php
class Application_Form_ConsumerNotes extends Main_Forms_Builder {

   public $_id;
   public $consumerId;
   public $userId;
   public $formType = 'Add';
    
    public function build( $action = "/consumer/note/new/",
                           $consumer_id = null,
                           $user_id = null,
                           $id = null,
                           $method= "post" ) {
       
       $this->_id = $id;
       $this->consumerId = $consumer_id;
       $this->userId = $user_id;
       
       $this->setName("consumer-note-form");
       $this->setMethod($method);
       $this->setAction($action);
       //$this->getData();
       $this->formElementsFromTable('consumers_notes', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();
    }

    public function getData() {
        
    }
  
  /**
    * Table: consumers_notes
    * Columns:
    * id	int(11) AI PK
    * consumer_id	int(11)
    * user_id	int(11)
    * note	text
    * created	timestamp
    *
    */
    public function getFields() {

    $fields = array("consumer_id" => array('default'=>$this->consumerId, 'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                    "goal_id" => array('required'=> false,  'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                    "user_id" => array('default'=>$this->userId, 'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                    "created" => array('default'=>date('Y-m-d'), 'type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),
                    "note"=>array('required'=> false, 'attributes'=>array('rows'=>'4', 'cols'=>'8')));
   
      if( isset( $this->_id ) ) {
         
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
