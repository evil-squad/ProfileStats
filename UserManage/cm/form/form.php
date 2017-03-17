<?php

	class UIForm
	{
		protected $name;

		private $upload=false;
		private $method;
		private $action=null;

		private $style_class="form-horizontal";

		private $width=6;

		private $panel_style=null;
		private $panel_title=null;

		public function __construct()
		{
			$a = func_get_args();
			$i = func_num_args();

			if (method_exists($this,$f='__construct'.$i))
				call_user_func_array(array($this,$f),$a);
		}

		public function __construct2($n,$m)
		{
			$this->name=$n;
			$this->method=$m;
		}

		public function __construct3($n,$m,$a)
		{
			$this->name=$n;
			$this->method=$m;
			$this->action=$a;
		}

		public function get_name()
		{
			return $name;
		}

		public function set_width($w)
		{
            $this->width=$w;
		}

		public function set_class($s)
		{
			$this->style_class=$s;
		}

		public function set_upload()
		{
            $this->upload=true;
		}

		public function set_panel_title($pt,$ps)
		{
            $this->panel_title=$pt;
            $this->panel_style=$ps;
		}

		public function add_hidden_value($input_name,$value)
		{
			echo '<input name="'.$input_name.'" type="hidden" value="'.$value.'"/>';
		}

		function add_edit_date($flag)
		{
			echo '<input type="text" name="'.$flag.'" value="'.date("Y-m-d H:i").'"/>';
		}

		function add_file_upload($id,$name)
		{
            echo '<input type="file" name="'.$name.'" id="'.$id.'"/>';
		}

		public function start()
		{
            if($this->width>0)
                echo '<div class="col-md-'.$this->width.' col-md-offset-'.((12-$this->width)/2).'">';

            if($this->panel_title)
            {
                echo '<div class="'.$this->panel_style.'">';
                echo '<div class="panel-heading">
                        <h3 class="panel-title">'.$this->panel_title.'</h3>
                    </div>
                    <div class="panel-body">';
            }

			echo '<form name="'.$this->name.'" method="'.$this->method.'" style="margin-bottom: 0px;" ';

			if($this->upload)echo 'enctype="multipart/form-data" ';
			if($this->action		!=null)echo 'action="'	.$this->action		.'" ';
			if($this->style_class	!=null)echo 'class="'	.$this->style_class	.'">';

			$_SESSION["final_submit"]=time();

			if($this->method=="post")        //get下不传递此参数
                $this->add_hidden_value('form_time',$_SESSION["final_submit"]);
		}

		public function submit_end($submit_name)
		{
			echo '<input type="submit" value="'.$submit_name.'" class="btn btn-primary" style="margin: 4px;"/>';
			echo '</form>';

			if($this->panel_title)
                echo '</div></div>';

            if($this->width>0)
                echo '</div>';
		}

		public function end()
		{
			echo '</form>';

			if($this->panel_title)
                echo '</div></div>';

            if($this->width>0)
                echo '</div>';
		}
	};//class UIForm

	function repeat_submit_check()
    {
        if($_SESSION["final_submit"]==$_POST['form_time'])
        {
            $_SESSION["final_submit"]=time();
            return true;
        }

        echo '请不要重复提交';
        return false;
    }

	function start_form_get($name,$action)
    {
		return(new UIForm($name,"get",$action));
    }

    function start_form_post($name,$action)
    {
		return(new UIForm($name,"post",$action));
    }

	function start_form_get_js($name,$action,$js_func)
    {
		return(new UIForm($name,"get",$action,$js_func));
    }

    function start_form_post_js($name,$action,$js_func)
    {
		return(new UIForm($name,"post",$action,$js_func));
    }
