<?php

class Base_Functions_Temp
{

   public static function saveImageTo($upload, $handle, $path, $scale=500)
   {
   
        //get the file name
        $fileInfo = $upload->getFileInfo($handle);

        //get temp name
        $temp = $fileInfo[$handle]['tmp_name'];

        //clean the file name
        $filename = Base_Functions_Files::fileNameCleaner($fileInfo[$handle]['name']);

        //resize the image to 500px and save it.     
        $res = Base_Functions_Assets::imageResizeSave($temp, $path, $scale);

        //store some info about the file. 
        $data = array('img'=>$res, "file"=>$filename);

        return array("success"=>true, "path"=>urlencode($path), "data"=>$data, "errors"=>array());
   
   }



    public static function deleteTempAsset($user_id, $asset_id, $vin_number)
    {
        //set up some placeholder vars. 
        $found  = false;
        $output = array('status'=>'fail', 'data'=>null, 
                        'id'=>$asset_id, 'errors'=>array());
                        
        //find the temp record set.
        $pending = Temp::getTempData($user_id, $vin_number);
        
        if(is_null($pending) || !isset($pending['data'])){
            $output['errors'][] = "Temp data not found!";
            return $output;
        }
        
        $data = $pending['data'];
        
        //search the temp index and find the ref. to the asset. 
        foreach( $data as $key=>$val ){
            if($asset_id == $val['id']){
                $found = true;
                $index = $key;
                break;          
            }
        }
        
        //if image found in temp array remove it from the temp array 
        //and delete the file. 
        if($found && isset($index))
        {
            if(!isset($pending['data'][$index]['path']))
            {
                $output['status'] = "fail";
                $output['errors'][] = "Missing Index in data array (path) not found!";
                return $output;
            }
        
            $path = UPLOAD_PATH.$pending['data'][$index]['path'];
           
            //if is file. 
            if(is_file($path))
                Base_Functions_Files::unlinkFile($path);

            //remove the old value from the pending array.
            unset($pending['data'][$index]);
            
            //rewrite the temp data.
            Temp::overWriteCumulativeTemp($pending['id'], $pending['data']);

            //format a response
            $output['status'] = "ok";
            $output['data'] = $pending;
            
        }else{
        
          $output['errors'][] = "Image not found in temp data!";

        }

       return $output;

    }




}
