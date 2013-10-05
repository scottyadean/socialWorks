<?php
	class Base_Functions_Files
	{
		/*Get file extension*/
		public static function fileExtension($name=NULL)
		{	if(isset($name))
			{	$f = basename($name);
				$e = pathinfo($f);
				return $e['extension'];
			}else{
				return false;
			}
		}
			
		/*Get file basename*/
		public static function fileBaseName($name=NULL)
		{	if(isset($name))
			{	return basename($name);
			}else{
				return false;
			}
		}
		
		public static function fileRename($oldname=NULL,$newname=NULL)
		{
			return isset($oldname) && isset($newname) ? rename($oldname,$newname) : false;
		}
		
		public static function fileNameCleaner($filename=NULL)
		{	if(!isset($filename))return false;
			$extension = NULL;
			$filename = basename($filename);
			$filename = preg_replace('/\s\s+/',' ',strtolower($filename));	
			$lastperiod =  substr_count($filename, '.'); 
			if($lastperiod > 0 )
			{	$tempname = explode(".",$filename);
				$extension = is_array($tempname) ? ".".array_pop($tempname) : NULL; 
				$filename = join("",$tempname);
			}
			$filename = preg_replace("/[^\-_a-z0-9-]/", "", $filename);			
			$filename = "{$filename}{$extension}";
			return $filename;
		}
		
		public static function recursiveChmod($path,$filePerm=0644, $dirPerm=0744)
		{		if(!file_exists($path)) return false;
				
				
				if(is_file($path)) chmod($path, $filePerm);
				if(is_dir($path)) 
				{
					
					$foldersAndFiles = scandir($path);
					$entries = array_slice($foldersAndFiles, 2);
					foreach($entries as $entry) self::recursiveChmod($path.DS.$entry, $filePerm, $dirPerm);
					chmod($path, $dirPerm);
				
				}
		
				return ture;
		}
		
		/*
// Read and write for owner, nothing for everybody else
chmod("/somedir/somefile", 0600);

// Read and write for owner, read for everybody else
chmod("/somedir/somefile", 0644);

// Everything for owner, read and execute for others
chmod("/somedir/somefile", 0755);

// Everything for owner, read and execute for owner's group
chmod("/somedir/somefile", 0750);
*/
		public static function filePermsTranslate($perms=NULL,$o=true,$g=false,$w=false)
		{			if(isset($perms))
					{/*Owner*/ 
						if($o)
						{
							$info .= (($perms & 0x0100) ? 'r' : '-');
							$info .= (($perms & 0x0080) ? 'w' : '-');
							$info .= (($perms & 0x0040) ?
													(($perms & 0x0800) ? 's' : 'x' ) :
													(($perms & 0x0800) ? 'S' : '-'));
						}
						/*Group*/ 
						if($g)
						{
							$info .= (($perms & 0x0020) ? 'r' : '-');
							$info .= (($perms & 0x0010) ? 'w' : '-');
							$info .= (($perms & 0x0008) ?
													(($perms & 0x0400) ? 's' : 'x' ) :
													(($perms & 0x0400) ? 'S' : '-'));
						}
						/*World*/ 
							if($w)
							{
							$info .= (($perms & 0x0004) ? 'r' : '-');
							$info .= (($perms & 0x0002) ? 'w' : '-');
							$info .= (($perms & 0x0001) ?
													(($perms & 0x0200) ? 't' : 'x' ) :
													(($perms & 0x0200) ? 'T' : '-'));
						}
					}							
							return $info;
		}
		
		
		public static function fileCreate($file,$contents)
		{	
			$open = fopen($file ,"w");
			 if(fwrite($open,$contents))
			  { 	fclose($open); 
				  return true; 
			  } else{ 
				  fclose($open); 
				  return false;
			  }
		}
		
		
		public static function fileRead($path)
		{
			$file = "{$path}";
			$contents = NULL;
			if(is_file($file))
			{	$size = filesize($file);
				$open = fopen($file, 'r');
				while ( ($buffer = fread( $open, $size )) != '' ){ $contents .= $buffer; }
				fclose($open);
				if($buffer===FALSE) return false;
				return $contents;
				
			}else{
			
				return false;
			}
		}
		
		public static function fileWrite($path,$contents=NULL)
		{	$file = "{$path}";
			if(is_file($file) && is_writable($file))
			{	$open = fopen($file, 'w');
				if(fwrite($open, $contents))
				{ 
					fclose($open); 
					return true; 
				} else{ 
					fclose($open); 
					return false;
				}
			}else{
			
				return false;
			}			
		}
	
		
		/* return the contents of a dir in an array */
		public static function listDirContents($handle=NULL)
		{	$list = array();
			$handle = isset($handle) && is_dir($handle) ? opendir($handle) :  NULL;
			if(isset($handle))
			{	while(false !== ($file = readdir($handle))) if($file != "." && $file != "..") $list[] = "{$file}";
				closedir($handle);
			}
			return $list;
		}
		
		
		/* Return recursive directory list inside a given directory as an array */
		public static function listDirRecursive($handle=NULL)
		{	$ignore = array( 'cgi-bin', '.', '..' );
			$files = array();
			if(isset($handle))
			{	$dh = @opendir($handle);
    			while(false !== ($file = readdir($dh))) if(!in_array($file, $ignore) && is_dir("{$handle}/{$file}"))$files[]="{$handle}/{$file}";
    			closedir($dh);
			}
			return $files;	
		} 
		
		/* Return recursive directory list and its files inside a given directory as an array */	
		public static function listDirAndFilesRecursive($directory,$recursive)
		{	$array_items = array();
			if ($handle = opendir($directory)) {
				while (false !== ($file = readdir($handle)))
				{	if ($file != "." && $file != "..") {
						if (is_dir("{$directory}/{$file}"))
						{	$filedir = "<b>{$directory}/{$file}</b>";
							$array_items[] = preg_replace("/\/\//si", "/", $filedir);
							if($recursive)$array_items = array_merge($array_items,self::listDirAndFilesRecursive("{$directory}/{$file}",$recursive));
						} else {
							$file = "{$file}";
							$array_items[] = preg_replace("/\/\//si", "/", $file);
						}
					}
				}
				closedir($handle);
			}
			return $array_items;
		}
		
		/*create a dir and chmod it */
		public static function createDir($directory, $overwrite=false, $chmod=0777)
		{	if(isset($directory) && !empty($directory))
			{	$is_dir = is_dir($directory);
				
				if(is_dir($is_dir) && !$overwrite) return false;
				
				if(!$is_dir){  return mkdir($directory, $chmod) ? true : false; } 
				
				if(is_dir($directory)  && !is_writable($directory))
				{	$old = umask(0);
					chmod("{$directory}", $chmod);
					umask($old);
					return $old != umask() ? false : true;
				}else{
					return true;
				}
			
			
			}else{
				return false;
			}	
		}
		
		/* Delete A Dir and all its contents*/
		public static function unlinkRecursive($dir=NULL,$deleteParentDir=false)
		{
			if(isset($dir))
			{
				if(!$dh = @opendir($dir))return false;
				
				while (false !== ($obj = readdir($dh)))
				{	if($obj == '.' || $obj == '..')continue;
					if(!@unlink($dir . '/' . $obj)) files::unlinkRecursive($dir.'/'.$obj, $deleteParentDir);
				}
				
				closedir($dh);
				if($deleteParentDir) @rmdir($dir);
				return true;
			
			}else{
				
				return false;
			}
		} 
		
		/* Delete A Single File*/
		public static function unlinkFile($file=NULL)
		{
			if(isset($file) && is_file($file) && !is_dir($file))
			{
				return @unlink($file) ? true : false;			
			}else{
				return false;
			}
		} 
		
			
		/* Get file Size */
		public static function fileSize($path)
		{	if(isset($path) && !empty($path) && file_exists($path))
			{	return filesize($path);
			}else{
				return false;
			}
		}
		/*Check the file size aganist max size attr. */
		public static function fileSizeCheck($f,$max_size)
		{	if(isset($f) && file_exists($f))
			{	$f_size = filesize($f);
				return intval($max_size) > $f_size ? true : false;
			}else{
				return false;
			}
		}
		
		/*Get Image mime type */
		public static function fileMime($path)
		{	if(function_exists("mime_content_type"))
			{	return mime_content_type($path);
			}else{
				return false;
			}
		}
			
		/* Determine gd gif draw version */	
		public static function createGDFile($w=50,$h=50)
		{	$i = gd_info();
			$g  = substr_count(strtolower($i['GD Version']), "2.");
			return $g>0 ? imagecreatetruecolor($w,$h) : imagecreate($w,$h);		
		}
		
		/* Create a gd image from src type */	
		 public static function createFrom($file,$type=1)
		 {	switch (intval($type))
			{	case 1: $src = imagecreatefromgif($file); break;
				case 2: $src = imagecreatefromjpeg($file);  break;
				case 3: $src = imagecreatefrompng($file); break;
				default:$src = false; break;
			}
			
			return $src;
		 }
		 
		/*Create the image according to the MIME type*/
		public static function createFromMime($file,$mime)
		{	
			switch($mime)
			{	case "image/jpg":
				case "image/jpeg":
				case "image/pjpeg":
					$src = imagecreatefromjpeg($file);
				break;
				case "image/gif":
					$src = imagecreatefromgif($file);
				break;
				case "image/png":
				case "image/x-png":
					$src = imagecreatefrompng($file);
				break;
				default:
					$src = imagecreatefromjpeg($file);
				break;
			}
			return $src;
		}	 
		
		/* Image Display */ 
		 public static function imageDisplay($file,$type=1)
		 {	switch (intval($type))
			{	case 2:
					header('Content-type: image/jpeg');
					imagejpeg($file, NULL, 100);	
				break;
				case 3:
					header('Content-type: image/png');
					imagepng($file, NULL, 100);
				break;
				case 1:
					header('Content-type: image/gif');
					imagegif($file, NULL, 100);
				break;
			}
		 		imagedestroy($file);
		 }
		 

		 
		/* Saves an Image File */ 
		public static function imageSave($temp=NULL,$src=NULL,$type=2)
		{
			switch (intval($type))
			{	case 2:
					@imagejpeg($temp, $src, 100);	
				break;
				case 3:
					@imagepng($temp, $src, 100);
				break;
				case 1:
					(function_exists('imagegif')) ? @imagegif($temp, $src, 100) : @imagejpeg($temp, $src, 100);
				break;
			}
		}	
	}
