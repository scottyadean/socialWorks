<?php
class Application_Form_Image extends Main_Forms_Builder {


   public $formType = 'Upload';
   public $row; //`relation_id`, `img`, `type`, `ext`

    public function build( $action = "/image/db/create",  $id = null, $method= "post" ) {
       
       
       $target = 'imguploadiframe';
       
       $this->row = $id;
       
       if ( !empty( $this->row )) {
            $this->formType = 'Update';
       }
     
       $this->setName("image-form");
       $this->setMethod($method);
       $this->setAction($action);
       $this->setAttrib('target', $target);

       
       $this->appendHtml(array('attributes'=>array(
                                'name'=>$target,
                                'id'=>'img-upload-iframe',
                                'tag'=>'iframe',
                                'class'=>'hidden',
                                'src'=>'/image/message',
                                'frameborder'=>0,
                                'width'=>'100%',
                                'height'=>'50px')));

       $this->formElementsFromTable('images', $this->getFields());
       $this->formElementsFromArray($this->getCustomFields());
       $this->createElements();


    }

    public function getFields() {

       return  array( "type"=>array('type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')),       
                      "img"=>array('label'=>'', 'type'=>'file', 'required'=> true ), 
                      "relation_id" => array('type'=>'hidden', 'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper')), 
                      "ext" => array('label'=>'','type'=> 'hidden', 'disableDecorator' => array('HtmlTag', 'DtDdWrapper')),
                      );
    }
    
    
    
        public function getCustomFields() {

  
        return array('submit' => array('label'=>$this->formType,
                                       'type'=>'submit',
                                       'name'=>'submit',
                                       'disableDecorator' => array('HtmlTag', 'Label', 'DtDdWrapper'), 
                                       'options' => array('class'=>'btn btn-small btn-primary'),
                                       'ignore'=>true)
                                    );
                                 
    }
    
    
}

