<?php

//     XXX         HP  MP  AP
//
//     3000    |        ___/
//     2000    |    ___/
//     1000    |___/
//        0    +---+---+---+---
//             A   B   C   D

    class ChartData
    {
        public $name;               //数据名称阵列(参加注释图中的HP/MP/AP)
        public $type;               //类型
        public $value;              //数据阵列
        public $stack=null;         //堆叠属性
        public $area_style=null;    //区域风格

        /**
        * @param n 数据名称
        * @param cols_value 列数据
        * @param t 数据类型
        **/
        public function __construct($n,$t,$cols_value)
        {
            $this->name=$n;
            $this->type=$t;
            $this->value=$cols_value;
        }
    };//class ChartData

    class Chart
    {
        private $id;
        private $width,$height;

        private $title=null;
        private $sub_text=null;

        private $tip_trigger=null;
        private $tip_formatter=null;

        private $category=null;             //分类位置
        private $item_name=null;            //数据项名称

        private $save_as_image=false;

        private $is_map=false;
        private $map_name=null;

        private $is_pie=false;
        private $pie_radius=null;

        private $vm_min,$vm_max;

        private $data_list=array();

        /**
        * @param n 名称(用于显示)
        * @param i id
        * @param w 宽
        * @param h 高
        **/
        public function __construct($n,$i,$w,$h)
        {
            $this->title=$n;
            $this->id=$i;
            $this->width=$w;
            $this->height=$h;
        }

        public function set_title($t)
        {
            $this->title=$t;
        }

        public function set_sub_text($t)
        {
            $this->sub_text=$t;
        }

        public function set_tooltip($tt,$tf)
        {
            $this->tip_trigger=$tt;
            $this->tip_formatter=$tf;
        }

        public function set_save_as_image($sai)
        {
            $this->save_as_image=$sai;
        }

        public function set_category($c,$in)
        {
            $this->category=$c;
            $this->item_name=$in;
        }

        public function set_visual_map($min,$max)
        {
            $this->vm_min=$min;
            $this->vm_max=$max;
        }

        public function set_map($mn)
        {
            $this->is_map=true;
            $this->map_name=$mn;
        }

        public function set_pie($radius)
        {
            $this->is_pie=true;
            $this->pie_radius=$radius;
        }

        public function add_data($name,$type,$data)
        {
            $cd=new ChartData($name,$type,$data);
            array_push($this->data_list,$cd);
            return $cd;
        }

        private function echo_legend()  //输出标题头
        {
            echo 'legend: { data:[';

            $first=true;
            foreach($this->data_list as $obj)
            {
                if($first==false)
                    echo ',';

                echo '"'.$obj->name.'"';
                $first=false;
            }

            echo ']},';
        }

        private function echo_grid()
        {
            echo '  grid:
                    {
                        containLabel:true
                    },';
        }

        private function echo_toolbox() //输出工具箱
        {
            echo 'toolbox:
                  {
                    feature:
                    {
                        saveAsImage:{}
                    }
                  },';
        }

        private function echo_visual_map()
        {
            echo 'visualMap:
                {
                    min: '.$this->vm_min.',
                    max: '.$this->vm_max.',
                    left: "left",
                    top: "bottom",
                    calculable: true
                },';
        }

        private function echo_category()
        {
            echo '  type:"category",';
            echo '  data:[';

            $first=true;
            foreach($this->item_name as $name)
            {
                if($first==false)
                    echo ',';
                else
                    $first=false;

                echo '"'.$name.'"';
            }

            echo ']';
        }

        private function echo_value()
        {
            echo '  type:"value"';
        }

        private function echo_axis($dir)
        {
            echo $dir.'Axis:[
                  {';

            if($this->category==$dir)       //如果分类在X
            {
                $this->echo_category();
            }
            else
            {
                $this->echo_value();
            }

            echo '}],';
        }

        private function echo_series()
        {
            echo 'series: [';

            $first=true;

            foreach($this->data_list as $obj)
            {
                if($first==false)
                    echo ',';
                else
                    $first=false;

                echo '{
                        name:"'.$obj->name.'",
                        type:"'.$obj->type.'",';

                if($this->is_map)
                {
                    echo 'mapType:"'.$this->map_name.'",
                          roam: false,
                          label:
                          {
                            normal:{show:true},
                            emphasis:{show:true},
                          },';
                }
                else
                if($this->is_pie)
                {
                    echo 'radius: "'.$this->pie_radius.'",
                          center:["50%","50%"],';
                }

                if($obj->stack)
                echo '  stack:"'.$obj->stack.'",';

                if($obj->area_style)
                echo '  areaStyle:{'.$obj->area_style.'},';

                echo '  data:[';

                $value_first=true;

                if($this->is_map||$this->is_pie)
                {
                    foreach($obj->value as $name=>$value)
                    {
                        if($value_first==false)
                            echo ',';
                        else
                            $value_first=false;

                        echo '{name:"'.$name.'",value:'.$value.'}
                        ';
                    }
                }
                else
                {
                    foreach($obj->value as $value)
                    {
                        if($value_first==false)
                            echo ',';
                        else
                            $value_first=false;

                        echo $value;
                    }
                }

                echo ']
                    }';
            }

            echo ']';
        }

        public function draw()
        {
            echo '<div id="'.$this->id.'" style="width:';

            if($this->width==0)
                echo '100%;height:';
            else
            if($this->width<=1)
                echo ($this->width*100).'%;height:';
            else
                echo $this->width.'px;height:';

            if($this->height==0)
                echo '100%;"></div>';
            else
            if($this->height<=1)
                echo ($this->height*100).'%;"></div>';
            else
                echo $this->width.'px;"></div>';

            echo '<script type="text/javascript">

                    var myChart=echarts.init(document.getElementById("'.$this->id.'"));

                    var option =
                    {
                    ';

            if($this->title)
            {
                echo 'title:{text:"'.$this->title.'"';

                if($this->sub_text)
                    echo ',subtext:"'.$this->sub_text.'"';

                echo '},';
            }

            echo 'tooltip:
                  {';
                if($this->tip_trigger)
                    echo 'trigger:"'.$this->tip_trigger.'",';

                if($this->tip_formatter)
                    echo 'formatter:"'.$this->tip_formatter.'"';
            echo '},';

            if($this->save_as_image)
                $this->echo_toolbox();

            if($this->is_map==false
             ||$this->is_pie==false)
            {
                $this->echo_grid();
                $this->echo_legend();

                if($this->category!=null)
                {
                    $this->echo_axis('x');
                    $this->echo_axis('y');
                }
            }

            if($this->is_map)
            {
                $this->echo_visual_map();
            }

            $this->echo_series();

            echo '};
                myChart.setOption(option);
                </script>';
        }
    };//class Chart
