<?php
class Application_Form_Consumer extends Main_Forms_Builder {


   public $formType = 'Add';
   public $row; 

    public function build( $action = "/consumer/new",  $id = null, $method= "post" ) {
       
       $this->row = $id;
       
       if ( !empty( $this->row )) {
            $this->formType = 'Edit';
       }
     
       $this->setName("consumer-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->formElementsFromTable('consumers', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();

    }

    public function getFields() {

       return  array( "type"=>array('required'=> true, 
                                    'type'=>'select',
                                    'multiOptions' => array('ils'=>'ILS', 'pa'=>'PA'), 'default'=>'ils'),       
                      "uci"=>array('label'=>"UCI #"),
                      "fname"=>array('label'=>'First Name', 'required'=> true ), 
                      "lname" => array('label'=>'Last Name', 'required'=> true), 
                      "address" => array('required'=> true),
                      "state" => array('required'=> true, 
                                       'type'=>'select', 
                                       'multiOptions' => Main_Forms_Data::AmericaStates(), 'default'=>'CA'),
                      "county"  => array('required'=> true),
                      "city"  => array('required'=> true), 
                      "zip"  => array('required'=> true), 
                      "phone"  => array('required'=> true), 
                      "email"  => array('required'=> true),
                      
                      "birth_date"=>array( 'required' => true, 'attributes'=>array('class'=>'date_widget') ),
                      "bio"  => array('label'=>'Notes', 'attributes'=>array('rows'=>'4', 'cols'=>'8')),
                      );
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
                                  'attributes'=>array('onclick'=>"history.back();"),
                                  'options' => array('class'=>'btn btn-small btn-primary'),
                                  'ignore'=>true       
                                 )
                                 );
                                 
                                 
    if(!empty($this->row )){
        $custom['id'] = array('type'=>'hidden',
                              'name'=>'id',
                              'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'),
                              'default'=>$this->row,
                              'required'=>true);
    }                             
                                 


    return $custom;


    }
    
    
}
