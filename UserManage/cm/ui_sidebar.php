<?php

    require_once "tools.php";
    require_once "tools_session.php";
    require_once "tools_json.php";

    class UISideBar
    {
        private $active_page=null;
        private $bar_list=null;

		private $sidebar_width;			//bootstarp中屏幕被分为12列宽，我们在PC桌面宽度环境，导航栏宽度设为1。手机/平板纵屏中，导航栏宽度设为2

        public function __construct($ap,$sw)
        {
            $this->active_page=$ap;
			$this->sidebar_width=$sw;
        }

        public function add($code,$text,$link)
        {
            $this->bar_list[$code]=array("link"=>$link,"text"=>$text);
        }

        public function set_active($code)
        {
            $this->active_page=$code;
        }

        public function to_json()
        {
            return json_encode($bar_list,JSON_UNESCAPED_UNICODE);
        }

        private function echo_item($code,$text,$link)
        {
			echo '<a class="list-group-item';

			if($code==$this->active_page)
				echo ' active';

			echo '" href="'.$link.'">'.$text.'</a>';
        }

        public function start()
        {
            if($this->bar_list==null)return;

            echo '<div class="row" style="margin-left: 0px; margin-right: 0px;">';
			echo '<div class="col-xs-'.$this->sidebar_width.' bs-docs-sidebar" style="padding-top: 15px;">
					<div class="list-group bs-docs-sidenav affix-top">';

            foreach($this->bar_list as $code=>$obj)
            {
                $link=$obj["link"];
                $text=$obj["text"];

                $this->echo_item($code,$text,$link);
            }

			echo '</div>
				</div>
					<div div class="col-xs-'.(12-$this->sidebar_width).'" style="padding-left: 0px;">';
        }

        public function end()
        {
			echo '</div>
                </div>';
        }
    };//class UISideBar
