<?php

    //参考：http://www.w3schools.com/bootstrap/bootstrap_navbar.asp

    function create_separator_menu()
    {
        return "-";
    }

    function create_menu($text)
    {
        return array("text"=>$text);
    }

    function create_menu_link($text,$link)
    {
        return array("text"=>$text,"link"=>$link);
    }

    function create_menu_sub($text,$sub_menu)
    {
        return array("text"=>$text,"sub_menu"=>$sub_menu);
    }

//     $member_menu=create_menu_sub("员工",array(create_menu("员工列表"),
//                                                 create_separator_menu(),
//                                                 create_menu("增加员工"),
//                                                 create_menu("删除员工"),
//                                                 create_menu("查找员工")));
//
//     $commodity_menu=create_menu_sub("商品",array(create_menu("商品列表"),
//                                                 create_separator_menu(),
//                                                 create_menu("增加商品"),
//                                                 create_menu("删除商品"),
//                                                 create_menu("查找商品")));
//
//     $supplier_menu=create_menu_sub("供应商",array(   create_menu("供应商列表"),
//                                                     create_separator_menu(),
//                                                     create_menu("增加供应商"),
//                                                     create_menu("删除供应商"),
//                                                     create_menu("查找供应商")));
//
//     $client_menu=create_menu_sub("客户",array(   create_menu("客户列表"),
//                                                     create_separator_menu(),
//                                                     create_menu("增加客户"),
//                                                     create_menu("删除客户"),
//                                                     create_menu("查找客户")));
//
//     $main_menu=array(  create_menu("欢迎"),
//                         $member_menu,
//                         $commodity_menu,
//                         $supplier_menu,
//                         $client_menu);
//
//     echo json_encode($main_menu,JSON_UNESCAPED_UNICODE);

    class UINavBar
    {
        private $style="navbar-default";
        private $fix_top=true;
        private $brand=null;
        private $brand_link=null;
        private $menu=null;
//        private $active=null;

        private $right_icon=null;
        private $right_icon_link=null;

        public function __construct($m)//,$a)
        {
            $this->menu=$m;
//             $this->active=$a;
        }

        public function set_style($s)
        {
            if($s==null||strlen($s)<=0)
                $this->style="";
            else
                $this->style="navbar-".$s;
        }

        /**
        * @b 标题文本
        * @l 标题链接
        */
        public function set_brand($b,$l)
        {
            $this->brand=$b;
            $this->brand_link=$l;
        }

        private function echo_menu($m)
        {
            if($m==null)return;

            foreach($m as $mi)
            {
                if(!is_array($mi))
                {
                    if($mi=="-")
                        echo '<li role="separator" class="divider"></li>';
                    else
                        echo '<li class="dropdown-header">'.$mi.'</li>';

                    continue;
                }

                if(array_key_exists("sub_menu",$mi))        //有子菜单
                {
                    echo '<li class="dropdown">';

                    echo '<a class="dropdown-toggle" data-toggle="dropdown" ';

                    if(array_key_exists("link",$mi))
                        echo 'href="'.$mi["link"].'" ';

                        echo 'role="button" aria-haspopup="true" aria-expanded="false">'.$mi["text"].'
                                <span class="caret"></span></a>
                                <ul class="dropdown-menu">';

                        $this->echo_menu($mi["sub_menu"]);

                    echo '</ul>
                        </li>';

                    continue;
                }

                if(array_key_exists("link",$mi))
                    echo '<li><a href="'.$mi["link"].'">'.$mi["text"].'</a></li>';
                else
                    echo '<li class="disabled"><a>'.$mi["text"].'</a></li>';
            }
        }

        public function set_right_icon($icon,$link)
        {
            $this->right_icon=$icon;
            $this->right_icon_link=$link;
        }

        public function out_html()
        {
            echo '<nav class="navbar '.$this->style.'">
                    <div class="container-fluid">';

            if($this->brand!=null&&strlen($this->brand)>0)
            {
                echo '<div class="navbar-header">';

                if($this->brand_link!=null&&strlen($this->brand_link)>0)
                    echo '<a class="navbar-brand" href="'.$this->brand_link.'">'.$this->brand.'</a>';
                else
                    echo '<font class="navbar-brand">'.$this->brand.'</font>';

                echo '</div>';
            }

            echo '<div class="navbar-collapse">
                    <ul class="nav navbar-nav">';

            if($this->menu!=null)
                $this->echo_menu($this->menu);

            echo '</ul>';

            if($this->right_icon!=null)
            {
                echo '<ul class="nav navbar-nav navbar-right">
                        <li><a href="'.$this->right_icon_link.'">';

                echo_icon($this->right_icon);

                echo '</a></li></ul>';
            }

            echo '</div>
              </div>
            </nav>';
        }
    };//class UINavBar
