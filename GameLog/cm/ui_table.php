<?php

    require_once "tools_sql.php";

    class TableCol
    {
        public $text;               //显示文本
        public $icon;               //图标
        public $link;               //如果点击列将跳转的页面

        public function __construct()
        {
            $this->text=func_get_arg(0);

            if(func_num_args()>1)$this->icon=func_get_arg(1);else $this->icon=null;
            if(func_num_args()>2)$this->link=func_get_arg(2);else $this->link=null;
        }
    };

    class UITable
    {
        protected $title_cols=null;
        private $heading=null;
        private $heading_style="default";
        private $body=null;
        private $table_style="striped";

        public function set_heading($h)
        {
            $this->heading=$h;
        }

        public function set_heading_style($hs)
        {
            $this->heading_style=$hs;
        }

        public function set_body_text($bt)
        {
            $this->body=$bt;
        }

        public function set_title_col_text($f)
        {
            foreach($f as $tt)
                $this->title_cols[]=new TableCol($tt);
        }

        public function set_title_col($f)
        {
            $this->title_cols=$f;
        }

        public function set_table_style($ts)
        {
            $this->table_style=$ts;
        }

        public function start()
        {
            echo '<div class="panel panel-'.$this->heading_style.'" style="margin-bottom: 0px;">';

            if($this->heading!=null)
                echo '<div class="panel-heading">'.$this->heading.'</div>';

            if($this->body!=null)
                echo '<div class="panel-body"><p style="margin-bottom: 0px;">'.$this->body.'</p></div>';

            if($this->table_style)
                echo '<table class="table table-responsive table-'.$this->table_style.'">';
            else
                echo '<table class="table table-responsive">';

            if($this->title_cols!=null)
            {
                echo '<tr>';
                    foreach($this->title_cols as $field)
                    {
                        echo '<th>';

                        if($field->link)
                            echo '<a href="'.$field->link.'">';

                        echo $field->text;

                        if($field->icon)
                            echo_icon($field->icon);

                        if($field->link)
                            echo '</a>';

                        echo '</th>';
                    }
                echo '</tr>';
            }
        }

        public function start_row   (){echo '<tr>';}
        public function end_row     (){echo '</tr>';}

        public function start_col   (){echo '<td>';}
        public function end_col     (){echo '</td>';}

        public function echo_col    ($text){echo '<td>'.$text.'</td>';}

        public function echo_row    ($text_list)
        {
            start_row();

                foreach($text_list as $text)
                    echo_col($text);

            end_row();
        }

        public function end()
        {
            echo '</table>
                </div>';
        }
    };//class UITable

    class UISQLTable extends UITable
    {
        private $sql_fields=null;
    	private $sql_result=null;

    	private $bool_text=array();
    	private $enum_text=array();

    	public function __construct()//$sql,$sql_table_name,$field_list,$where,$start,$count)
    	{
            $sql=func_get_arg(0);
            $sql_table_name=func_get_arg(1);

            if(func_num_args()>2)$field_list=func_get_arg(2);else $field_list=null;
            if(func_num_args()>3)$where     =func_get_arg(3);else $where=null;
            if(func_num_args()>4)$start     =func_get_arg(4);else $start=null;
            if(func_num_args()>5)$count     =func_get_arg(5);else $count=null;

    		if($field_list==null)
    		{
    			$field_list=get_field_list($sql,$sql_table_name);

                parent::set_title_col($field_list);

                $this->sql_fields=$field_list;

    			$this->sql_result=select_table_to_array($sql,$sql_table_name,null,$where,$start,$count);
    		}
    		else
    		{
                parent::set_title_col($field_list);

                $this->sql_fields=$field_list;

    			$this->sql_result=select_table_to_array($sql,$sql_table_name,$field_list,$where,$start,$count);
    		}
    	}

    	public function SetBoolText($field,$true_text,$false_text)
    	{
    		$this->bool_text[$field]=array($false_text,$true_text);
    	}

    	public function SetEnumText($field,$text_array)
    	{
            $this->enum_text[$field]=$text_array;
    	}

    	public function get_sql_result()
    	{
    		return $this->sql_result;
    	}

	    public function out_html()
	    {
	    	parent::start();

	    	for($r=0;$r<count($this->sql_result);$r++)
	    	{
	    		$row=$this->sql_result[$r];

	    		parent::start_row();
	    		for($c=0;$c<count($row);$c++)
	    		{
                    parent::start_col();

	    			if(array_key_exists($this->sql_fields[$c],$this->bool_text))
	    			{
	    				echo $this->bool_text[$this->sql_fields[$c]][$row[$c]];
	    			}
	    			else
	    			if(array_key_exists($this->sql_fields[$c],$this->enum_text))
	    			{
                        echo $this->enum_text[$this->sql_fields[$c]][$row[$c]];
	    			}
	    			else
	    			{
	    				echo $row[$c];
	    			}

	    			parent::end_col();
	    		}
	    		parent::end_row();
	    	}

	    	parent::end();
	    }
    };//class UISQLTable
?>
