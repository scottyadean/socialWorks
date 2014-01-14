<?php
class Base_Template_Paginate
{
	public $current_page;
	public $per_page;
	public $total_count;
	
	public function __construct($per_page=20, $total_count=0, $curr_page=1)
	{	$this->current_page	= (int)$curr_page;
		$this->per_page		= (int)$per_page;
		$this->total_count	= (int)$total_count;
	}
	
	public function offset()
	{
		return ($this->current_page -1) * $this->per_page;
	}				
	
	public function links($self = '', $html = '')
	{	$html .= '<div class="pagination"><ul>';
		if($this->total_pages() > 1)
		{
			
			if($this->has_previous_page()){
			
				$html .=  "<li><a href='{$self}/{$this->previous_page()}'>back</a></li>";
			
			}else{
				
				$html .=  "<li class='disabled'><a>back</a></li>";	
			}
			
			
			for($i=1; $i <= $this->total_pages(); $i++){
				$html.= $i==$this->current_page ? "<li class='active'><a>{$i}</a></li>"
				:" <li><a href='{$self}/{$i}'>{$i}</a><li>";
			}
			
			if($this->has_next_page()){
				$html .= "<li><a href='{$self}/{$this->next_page()}'>next</a></li>";
			}else{
				
				$html .= "<li class='disabled'><a>next</a></li>";	
			}
			
			
		}
		
		$html .= '</ul></div>';
		
		return $html;
	}
	
	
	public function get_offset()
	{	
		return (int)$this->offset();
	}
	
	public function get_limit()
	{	
		return (int)$this->per_page;
	}
	
	
	public function total_pages()
	{
		return ceil($this->total_count/$this->per_page);
	}
	
	public function next_page()
	{
		return $this->current_page + 1;
	}
	
	public function has_next_page()
	{
		return $this->next_page() <= $this->total_pages() ? true : false;
	}
	
	public function previous_page()
	{
		return $this->current_page - 1;
	}
		
	public function has_previous_page()
	{
		return $this->previous_page() >= 1 ? true : false;
	}	
		
}		