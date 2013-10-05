<?php 
class Base_Template_Render 
{
    public  $temproot;    
    public  $tempfile;
    public  $template;
    public  $output;

    public function __construct($template = false, $module_path=false, $ext=".phtml")
    {
        $this->temproot = APPLICATION_PATH."/templates/";
        

        if($module_path)
            $this->temproot = APPLICATION_PATH."/modules/".$module_path."/views/scripts/templates/";        
 
        $this->tempfile = $this->temproot.'default/index.phtml';
        $this->template = !$template || !file_exists($this->temproot.$template.$ext) ? $this->tempfile : $this->temproot.$template.$ext;
    } 
   
	public function parseTemplate($tags=array())
	{	
        $this->output = file_get_contents($this->template) or die('Error: Template file '.$this->template.' not found!');
        $inputs = array();
		foreach($tags as $tag=>$data)
		{	
            if(file_exists($data)) { $data = self::parseFile($data); } 
			$this->output = str_replace("{".$tag."}","${data}",$this->output);
	    }

	}

	public function display()
	{
	   return $this->output;
	}

	private static function parseFile($file)
	{
		ob_start();
	  	include($file);
	  	$content = ob_get_contents();
	  	ob_end_clean();
	  	return $content;
    }


}
