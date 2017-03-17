<?php

    class UIEditBox
    {
        public $type="text";
        public $name=null;
        public $label=null;
        public $label_width=2;
        public $edit_width=10;
        public $size=0;
        public $value=null;
        public $place_holder=null;
        public $hint=null;

		public function __construct()
		{
			$a = func_get_args();
			$i = func_num_args();

			if (method_exists($this,$f='__construct'.$i))
				call_user_func_array(array($this,$f),$a);
		}

        public function __construct3($t,$n,$l)
        {
            $this->type=$t;
            $this->name=$n;
            $this->label=$l;
        }

        public function __construct4($t,$n,$l,$v)
        {
            $this->type=$t;
            $this->name=$n;
            $this->label=$l;
            $this->value=$v;
        }

        public function __construct5($t,$n,$l,$v,$s)
        {
            $this->type=$t;
            $this->name=$n;
            $this->label=$l;
            $this->value=$v;
            $this->size=$s;
        }

        public function out_html()
        {
            echo '<div class="form-group" style="margin-left: 0px; margin-right: 0px;">';

            if($this->label)
                echo '<label class="control-label col-sm-'.$this->label_width.'">'.$this->label.'</label>
                        <div class="col-sm-'.$this->edit_width.'">';

                echo '<input type="'.$this->type.'" class="form-control" name="'.$this->name.'"';

            if($this->size>0)
                echo ' size="'.$this->size.'"';

            if($this->value)
                echo ' value="'.$this->value.'"';

            if($this->place_holder)
                echo ' placeholder="'.$this->place_holder.'"';

            echo '>';

            if($this->hint)
                echo '<span class="help-block">'.$this->hint.'</span>';

            if($this->label)
                echo '</div>';

            echo '</div>';
        }
    };//class UIEditBox

	function CreateInputGroup()
	{
        $edit=new UIEditBox();

    	$edit->type  =func_get_arg(0);
    	$edit->name  =func_get_arg(1);
    	$edit->label =func_get_arg(2);

    	if(func_num_args()>3)
            $edit->size  =func_get_arg(3);

    	if(func_num_args()>4)
            $edit->place_holder=func_get_arg(4);
        else
            $edit->place_holder=null;

        if(func_num_args()>5)
            $edit->right_label=func_get_arg(5);
        else
            $edit->right_label=null;

        $edit->out_html();
	}
?>
