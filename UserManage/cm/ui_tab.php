<?php

    class UITabView
    {
        private $id=null;
		private $active=null;
		private $tab_array=null;

		public function __construct($i,$a)
		{
			$this->id=$i;
			$this->active=$a;
		}

        public function set_tab($ta)
        {
            $this->tab_array=$ta;
        }

        public function out_html()
        {
            echo '<div id="'.$this->id.'">
                    <ul class="nav nav-tabs">';

            foreach($this->tab_array as $label=>$text)
            {
                echo '<li';

                if($label==$this->active)
                    echo ' class="active"';

                echo '><a href="'.$label.'">'.$text.'</a></li>';
            }

            echo '</ul>
                </div>';
        }
    };//class UITabView

    function create_tab_view($id,$active,$tab_array)
    {
        $tab=new UITabView($id,$active);

        $tab->set_tab($tab_array);

        $tab->out_html();
    }
?>
