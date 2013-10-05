<?php

class Base_Functions_Assets
{
	public $img = NULL;
	public $typ = 1;
	
	public static $currentAssets;
	
	

	/*Return and cache a thubnail */
	public static function thumbnail($filename=NULL,$thumb_size=0,$quality=0,$image_x=NULL,$image_y=NULL,$nocache=false,$noErrorDebug=false)
	{	$DS =  DIRECTORY_SEPARATOR;
		ini_set('memory_limit','64M');
		$cache = APPLICATION_PATH.'/image_cache/';
		$image_error = false;
		$nofilename = $cache.'default.gif';

		/*thumbnail x/y ratio */
		$thumb_size_x = $thumb_size_y = 0;
		/*thumbnail size default = 100 */
		$thumb_size = $thumb_size!=0 ? intval($thumb_size) : 100;
		/*image quality  default  = 80*/
		$quality = $quality!=0 ? intval($quality) : 80;
		/*image file path and name */
		$filename = isset($filename) && file_exists($filename) ? $filename :  $nofilename;
		/*if x set iamge x/y */
		if(isset($image_x))
		{$thumb_size_x = intval($image_x); $thumb_size_y = isset($image_y) && intval($image_y) > 0 ?  intval($image_y) : $thumb_size_x;
		}
		/*get the file name and ext.*/
		$fileextension = substr($filename, strrpos ($filename, ".") + 1);
		/*cache the image file*/
		$cach_path = $cache.$DS.md5($filename.@$thumb_size.@$thumb_size_x.@$thumb_size_y.@$quality).'.'.$fileextension; 	
		$cache_file = str_replace('//','/',$cach_path);
		/* remove cache thumbnail */
		if($nocache && file_exists($cache_file)) unlink($cache_file);  

		/* if cached thumbnail */
		if (!$nocache && (file_exists($cache_file)))
		{	header("Content-type: image/{$fileextension}");
			header("Expires: Mon, 26 Jul 2030 05:00:00 GMT");   
			header('Content-Disposition: inline; filename='.str_replace('/','',md5($filename.@$thumb_size.@$thumb_size_x.@$thumb_size_y.@$quality).'.'.$fileextension));
			print (join('',file($cache_file)));
			exit;
		}

		/* determine php and gd versions */
		if(intval(str_replace(".","",phpversion())) >= 430 ) $gd_version = gd_info();
	
		/* exit on error */
		if (!$image_type_arr = getimagesize($filename))
		{
			header('Content-type: image/gif');
			print(join('',file($filename)))or die('Error');		
		} 
	
	switch ($image_type_arr[2])
	{	case 2: /* JPG */
			if (!$image = @imagecreatefromjpeg ($filename))
			{	# not a valid jpeg file
				$image = imagecreatefrompng ($image_error);
				$file_type="png";
				if (file_exists($cache_file))unlink($cache_file);
			} 
		break;
		case 3: /* PNG */
			if (!$image = @imagecreatefrompng ($filename))
			{	# not a valid png file
				$image = imagecreatefrompng ($image_error);
				$file_type="png";			
				if (file_exists($cache_file)) unlink($cache_file);
			}			 
		break;
		case 1: /* GIF */ 
			if (!$image = @imagecreatefromgif ($filename))
			{	# not a valid gif file
				$image = imagecreatefrompng ($image_error);
				$file_type="png";			
				if (file_exists($cache_file))unlink($cache_file);
			}			 
		break;
		default:
			$image = imagecreatefrompng($image_error); 
		break;
	}

	/*define size of original image	*/
	$image_width = imagesx($image); $image_height = imagesy($image);

	/*define size of the thumbnail*/
	if (@$thumb_size_x>0)
	{	/* define images x AND y*/
		$thumb_width = $thumb_size_x;
		$factor = $image_width/$thumb_size_x;
		$thumb_height = intval($image_height / $factor); 
		if ($thumb_height>$thumb_size_y)
		{	$thumb_height = $thumb_size_y; 
			$factor = $image_height/$thumb_size_y;
			$thumb_width = intval($image_width / $factor); 
		}		
	} else {
		/* define images x OR y */
		$thumb_width = $thumb_size; 
		$factor = $image_width/$thumb_size;
		$thumb_height = intval($image_height / $factor); 
		if ($thumb_height>$thumb_size)
		{	$thumb_height = $thumb_size; 
			$factor = $image_height/$thumb_size;
			$thumb_width = intval($image_width / $factor); 
		}
	}
	/* create the thumbnail */
	if ($image_width < 4000)
	{	if (substr_count(strtolower($gd_version['GD Version']), "2.")>0)
		{	/*GD 2.0 */
			$thumbnail = ImageCreateTrueColor($thumb_width, $thumb_height);
			imagecopyresampled($thumbnail, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);
		} else {
			/*GD 1.0 */
			$thumbnail = imagecreate($thumb_width, $thumb_height);
			imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);			
		}	
	} else {
		if (substr_count(strtolower($gd_version['GD Version']), "2.")>0)
		{	/*GD 2.0 */
			$thumbnail = ImageCreateTrueColor($thumb_width, $thumb_height);
			imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);
		} else {
			/*GD 1.0 */
			$thumbnail = imagecreate($thumb_width, $thumb_height);
			imagecopyresized($thumbnail, $image, 0, 0, 0, 0, $thumb_width, $thumb_height, $image_width, $image_height);
		}
	}

	/*if is set $imageStr add the String text*/
	if (isset($string) && !empty($string))
	{	$white = imagecolorallocate ($thumbnail, 255, 255, 255);
		$black = imagecolorallocate ($thumbnail, 0, 0, 0);
		imagestring ($thumbnail, 1, 3, $thumb_height-9, trim($string), $black);
		imagestring ($thumbnail, 1, 2, $thumb_height-10, trim($string), $white);
	}

	switch ($image_type_arr[2])
	{	case 2:	/*JPG*/
			header('Content-type: image/jpeg');
			header('Content-Disposition: inline; filename='.str_replace('/','',md5($filename.$thumb_size.$thumb_size_x.$thumb_size_y.$quality).'.jpeg'));
			@imagejpeg($thumbnail,$cache_file, $quality);
			imagejpeg($thumbnail,'',$quality);
	
		break;
		case 3: /*PNG*/
			header('Content-type: image/png');
			header('Content-Disposition: inline; filename='.str_replace('/','',md5($filename.$thumb_size.$thumb_size_x.$thumb_size_y.$quality).'.png'));
			@imagepng($thumbnail,$cache_file);
			imagepng($thumbnail); 
		break;
		case 1:	/*GIF*/ 
			if (function_exists('imagegif'))
			{	header('Content-type: image/gif');
				header('Content-Disposition: inline; filename='.str_replace('/','',md5($filename.$thumb_size.$thumb_size_x.$thumb_size_y.$quality).'.gif'));
				@imagegif($thumbnail,$cache_file);
				imagegif($thumbnail);  
			} else {
				header('Content-type: image/jpeg');
				header('Content-Disposition: inline; filename='.str_replace('/','',md5($filename.$thumb_size.$thumb_size_x.$thumb_size_y.$quality).'.jpg'));
				@imagejpeg($thumbnail,$cache_file);
				imagejpeg($thumbnail); 
			}
		break;
	}

	/*clear memory*/
	imagedestroy ($image);
	imagedestroy ($thumbnail);

			
	}/*end thumbnail method*/

        public static function saveImageTo($upload, $handle, $path, $scale=500)
        {
        
             //get the file name
             $fileInfo = $upload->getFileInfo($handle);
     
             //get temp name
             $temp = $fileInfo[$handle]['tmp_name'];
     
             //clean the file name
             $filename = Base_Functions_Files::fileNameCleaner($fileInfo[$handle]['name']);
     
             //resize the image to 500px and save it.     
             return self::imageResizeSave($temp, $path, $scale);
     
     
        
        }


	
	/*Resize and save an image*/
	public static function imageResizeSave($src=NULL, $path=NULL, $size=500)
	{	/*Get max W x H  */
		$mw = $mh = $size;
		/*Get Dimensions and type */ 
		list($w, $h, $type) = getimagesize($src);
		
                /*if w/h larger then max height || max width resize W/ aspect ratio */
		if($w > $mw || $h > $mh)
		{	($mw && ($w < $h)) ? $mw =($mh/$h) * $w : $mh = ($mw/$w) * $h;
		}else{
			$mw = $w; $mh = $h;	
		}
		
                /* New Copy */
		$new = Base_Functions_Files::createFrom($src,$type);
		
                /* Temp Canvas */
		$temp = Base_Functions_Files::createGDFile($mw,$mh);
		
                /* Resample */
		imagecopyresampled($temp, $new, 0, 0, 0, 0, $mw, $mh, $w, $h);
                
                
                
                if( $path != 'db' ){
                
                    /*overwrite the src file with the temp file*/
                    Base_Functions_Files::imageSave($temp,$path,$type);
                    
                    /*clear memory*/
                    imagedestroy($new);
                    imagedestroy($temp);
                    
                    return true;
		
                }else{
                   
                    /*clear memory*/
                    imagedestroy($new);
                    ob_start();
                        imagejpeg($temp, null, 100);
                    $imageData = ob_get_clean();
                    
                    
                    imagedestroy($temp);
                    
                    return  $imageData;
                    
                    
                }
                
               
		
                return false;
	} 
	
	/*Rotate an image */
	public static function rotate($resource,$degree=0)
	{	$resource = isset($degree) ? imagerotate($resource, intval($degree), 0) : $resource;
		return $resource;
	} 
	
	/*Resize save and crop an image */
	public function crop($src,$nw=50,$nh=50,$x,$y,$rotate=NULL,$save=false,$as=NULL)
	{	
		/* Get image info */
		list($w, $h, $type) = getimagesize($src);
		/*Create image from src */
		$new = Base_Functions_Files::createFrom($src,$type);
		/*Create a temp image*/
		$temp = Base_Functions_Files::createGDFile($nw,$nh);
		/* Resample */
		imagecopyresampled($temp, $new, 0, 0, $x, $y, $nw, $nh, $nw, $nh);
		/* Rotate */
		if(isset($rotate))$temp = image::rotate($temp,$rotate);
		/*Save the new image file */
		if($save){$as = isset($as) ? Base_Functions_Files::imageSave($temp,trim($as),$type) : Base_Functions_Files::imageSave($temp,$src,$type);}
		/* Capture the temp image in a public var */
		$this->img = $temp;
		$this->typ = $type;
		/*Clear memory*/
		imagedestroy($new);
		//imagedestroy($temp);
	}  
	

	public static function uploadAsset($post_id, $vin_number, $user)
	{
        $output = array( 'status'=>false, 'errors'=>array() );

        //get the image file 
        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->addValidator('MimeType', false, array('image/gif', 'image/jpeg'));
        $upload->addValidator('Size',false,array('max' => '4MB','bytestring' => false));

       
        
        //check if they hit the upload limit
        $assetCount = count(self::getAssetNames($user['dir'],$post_id));
        if( $assetCount >= UPLOAD_LIMIT ) {
           return  array( 'status'=>false, 'errors'=>array("UploadLimit"), 'limit'=>UPLOAD_LIMIT);
        }
        
        
         
        //check if its a valid post. 
        if($upload->isValid())
        {   
            if($post_id == 'temp')
            {   

                $output = self::saveTempAsset($upload, $user);
                
            }else{

                $output = self::saveAssetForExistingListing($upload, 
                                                            $post_id,
                                                            $user);   
            }
            
        }else{

            $output['errors'] = $upload->getErrors();

        }        
               
       
	    return $output;
	}
	
	public static function saveAssetForExistingListing($upload, $post_id, $userInfo)
    {
        $output = array( 'status'=>false, 'errors'=>array(), 'limit'=>UPLOAD_LIMIT );
        
        //check for a valid listing id.
        if((int)$post_id == 0) {
           $output['errors'][] =  'InvalidPostID';
           return $output;            
        }
        
        
        
        //get the file info.
        $fileInfo = $upload->getFileInfo('asset_upload');
        $filename = Base_Functions_Files::fileNameCleaner($fileInfo['asset_upload']['name']);
        $temp = $fileInfo['asset_upload']['tmp_name'];


        //check if the file exists 
        $overwrite = self::fileExists($userInfo['dir'], $post_id, $filename); 
        
        //create the dir is they are not created.            
        Base_Functions_Files::createDir($userInfo['dir']);
        Base_Functions_Files::createDir($userInfo['dir'].DS.$post_id);

        $rel  = $userInfo['mmid'].DS.$post_id.DS.$filename;                
        $path = $userInfo['dir'].DS.$post_id.DS.$filename;

        Base_Functions_Assets::imageResizeSave($temp, $path, 500); 

        $attributes = array('listing_id'=>$post_id, 'path'=>$rel);
        //$a = new AssetListing($attributes);
        //$a->save();

        return array("status"=>true, 
                     "name"=>$filename,
                     'limit'=>UPLOAD_LIMIT,
                     "pointer"=>urlencode($rel), 
                     "data"=>$attributes, 
                     "id"=>$post_id, 
                     "overwrite"=>$overwrite, 
                     "errors"=>array());

    } 

    public static function saveTempAsset($upload, $userInfo=false){
    
        
        //get the file name
        $fileInfo = $upload->getFileInfo('asset_upload');

        //get the user info
        if(!$userInfo)
            $userInfo = Base_Auth_Member::info();

        //get temp name
        $temp = $fileInfo['asset_upload']['tmp_name'];

        //clean the file name
        $filename = Base_Functions_Files::fileNameCleaner($fileInfo['asset_upload']['name']);

        //check if the file exists 
        $overwrite = self::fileExists($userInfo['dir'], "temp", $filename); 

        //set up the relitive path and abs path
        $rel  = $userInfo['mmid'].DS.'temp'.DS.$filename;                
        $path = $userInfo['dir'].DS.'temp'.DS.$filename;

        //create the user dir
        Base_Functions_Files::createDir($userInfo['dir']);
        Base_Functions_Files::createDir($userInfo['dir'].DS.'temp');

        //resize the image to 500px and save it.     
        Base_Functions_Assets::imageResizeSave($temp, $path, 500);

        //store some info about the file. 
        $fileData = array("listing_id"=> 0, 
                          "id"=>preg_replace("/[^0-9A-Za-z]/","_",$filename),
                          "uid"=>uniqid('img_'), 
                          "path"=>$rel, 
                          "file"=>$filename);

        return array("status"=>true,
                     'limit'=>UPLOAD_LIMIT, 
                     "name"=>$filename,
                     "pointer"=>urlencode($rel), 
                     "id"=>$fileData['id'], 
                     "data"=>$fileData, 
                     "overwrite"=>$overwrite, 
                     "errors"=>array());
    
    }

	
	public static function moveTempAssets( $user, $listing_id, $vin_number )
	{   

        $pending = Temp::getTempData($user['id'], $vin_number);

        //create the dir is they are not created.            
        Base_Functions_Files::createDir($user['dir']);
 
        if(is_dir($user['dir'].DS.'temp'));
            @rename($user['dir'].DS.'temp', $user['dir'].DS.$listing_id);

        if(isset($pending['id']))
            Temp::deleteTempData((int)$pending['id']);


       return $user['dir'].DS.$listing_id;
	}
	

   
   public static function fileExists( $dir, $id , $name ){ 
   
        return in_array($name,self::getAssetNames($dir,$id));
   
   }
   
   
   public static function getAssetNames($dir,$id) {
   
        if(is_null(self::$currentAssets))
            self::$currentAssets = Base_Functions_Files::listDirContents($dir.DS.$id);
   
        return self::$currentAssets;
   
   }


	public static function getAssets( $dir, $id, $mmid, $size=50 )
	{   
	
	
        $files = Base_Functions_Files::listDirContents($dir.DS.$id);
        $assets = array();        

        foreach( $files as $key=>$img ) {
        
            $assets[] = array( 'base_name'=>$img, 
                               'base_dir'=>$mmid,
                               'base_id'=>$id, 
                               'src'=> BASE_PATH.'/media/file/asset/?file='.urlencode($mmid.DS.$id.DS.$img).'&size='.$size);
        
        }


       return $assets;
	}




	public static function getSingleAssets( $dir, $id, $size=50 ) {   
	
	    $files = Base_Functions_Files::listDirContents(UPLOAD_PATH.$dir.DS.$id);
        
        if( is_array( $files ) && count($files) > 0 ){ 
        
            return $dir.DS.$id.DS.$files[0];
        }


       return false;
	}




	public static function displayAssetFullPath($file, $size, $quality=100) {
	      
       Base_Functions_Assets::thumbnail($file,$size,$quality,NULL,NULL,false,false); 
	
	}

	
	public static function displayAsset($file, $size, $quality=100) {
	      
        if($file)
            Base_Functions_Assets::thumbnail(UPLOAD_PATH.DS.urldecode($file),$size,$quality,NULL,NULL,false,false); 
	
	}
	

	public static function getAssetPath($dir) {
	      
        return UPLOAD_PATH.DS.$dir;
	
	}


}
?>
