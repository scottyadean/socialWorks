<?php
class Main_Forms_Handler {
    
    public static function onPost($form,
                                  $isPost,
                                  $model,
                                  $action,
                                  $params,
                                  $helper,
                                  $uri="/",
                                  $msg ="",
                                  $xhr = false,
                                  $error = false )  {
                
                if( $isPost ){
                     $form->populate($form->getValues());
                }else{
                    $form->populate($params);
                }
            
                if( $error != false ) {
                    $helper->flashMessenger->addMessage(array('alert alert-error'=> $error) );  
                    return false;                
                }
    
            if( $isPost && $form->isValid($params)  ) {
    
                if( $lastid = $model->$action($form->getValues())){
                    
                    if($xhr){
                        
                        $id =  isset($params['id']) && !empty($params['id']) ? $params['id'] : $lastid;
                        return array( 'id'=>$id,
                                      'success'=>$lastid,
                                      'message'=>$msg,
                                      'action'=>$action,
                                      'values'=>$form->getValues()); 
                    }
                    
                    $helper->flashMessenger->addMessage(array('alert alert-success'=> $msg) );  
                    $helper->redirector->gotoUrl($uri);
                       
                }
            }else{
                
                    //on error is ajax return form and errors.
                    if($xhr){
                        
                            $id =  isset($params['id']) && !empty($params['id']) ? $params['id'] : 0;
                            return array( 'id' => $id,
                                          'success'=>false,
                                          'message'=>'Error Performing '.$action,
                                          'action'=>$action,
                                          'errors'=>$form->getErrors(),
                                          'values'=>$form->getValues()); 
                        
                               
                        
                     }        
                
            } 
    }
}