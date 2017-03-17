<?php

	class UIButton
	{
		private $style='class="btn"';
		private $text="";
		private $onclick="";

		public function SetText($t)
		{
			$this->text=$t;
		}

		public function SetGlyph($g)
		{
			$this->text='<span class="glpyhicon glyphicon-'.$g.'"></span>';
		}

		public function SetStyle($bs)
		{
			if($bs==null)
				$this->style='class="btn"';
			else
				$this->style='class="btn btn-'.$bs.'"';
		}

		public function SetCircleStyle($bs)
		{
			if($bs==null)
				$this->style='class="btn btn-circle"';
			else
				$this->style='class="btn btn-'.$bs.' btn-circle"';
		}

		public function SetOnClick($js)
		{
			$this->onclick='onclick="'.$js.'"';
		}

		public function SetOpenWindow($link)
		{
			$this->onclick='onclick="javascript:window.open(\''.$link.'\')"';
		}

		public function SetLocalLink($link)
		{
			$this->onclick='onclick="javascript:location.href=\''.$link.'\'"';
		}

		public function out_html()
		{
			echo '<button '.$this->style.' '.$this->onclick.'>'.$this->text.'</button>';
		}
	};//class UIButton

	//function create_js_button($text,$js_func,$style)
	function create_js_button()
	{
		$text=func_get_arg(0);
		$js_func=func_get_arg(1);

		if(func_num_args()>2)
			$style=func_get_arg(2);
		else
			$style="primary";

		echo '<button type="button" onclick="'.$js_func.'" class="btn btn-'.$style.'">'.$text.'</button>';
	}
?>
