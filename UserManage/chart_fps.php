<?php

    require_once "cm/phptools.php";
    require_once "config.php";

    function get_data_array($sql,$sql_table,$ct_list,$field,$where_mode,$total,$sql_where)
    {
        $per=false;
        $div=1;

        if(strcmp($where_mode,'>='  )==0)   $where=$field.">=";else
        if(strcmp($where_mode,'<='  )==0)   $where=$field."<=";else
        if(strcmp($where_mode,'>=%' )==0){  $where="FLOOR(".$field.")>=";   $per=true;  }else
        if(strcmp($where_mode,'<=%' )==0){  $where="FLOOR(".$field.")<=";   $per=true;  }else
        if(strcmp($where_mode,'5'   )==0){  $where="FLOOR(".$field."/5)=";  $div=5;     }else
        if(strcmp($where_mode,'10'  )==0){  $where="FLOOR(".$field."/10)="; $div=10;    }else
                                            $where="FLOOR(".$field.")=";

        if($sql_where)
            $where=$sql_where.' AND '.$where;

        $ct_count=array();

        if($per)
        {
            foreach($ct_list as $ct)
            {
                if(strlen($ct)<=0)continue;
                $ct_count[$ct]=sql_get_field_count($sql,$sql_table,$field,$where.$ct)*100/$total;
            }
        }
        else
        if($div==1)
        {
            foreach($ct_list as $ct)
            {
                if(strlen($ct)<=0)continue;

                $ct_count[$ct]=sql_get_field_count($sql,$sql_table,$field,$where.$ct);
            }
        }
        else
        {
            foreach($ct_list as $ct)
            {
                if(strlen($ct)<=0)continue;

                $ct_count[($ct*$div).' to '.(($ct*$div)+($div-1))]=sql_get_field_count($sql,$sql_table,$field,$where.$ct);
            }
        }

        return $ct_count;
    }

    function draw_chart($name,$text,$type,$ct_list,$sql,$sql_table,$field,$where,$total,$sql_where)
    {
        echo '<div class="col-sm-6">';

        if(count($ct_list)<=1)
        {
            echo '没有数据';
        }
        else
        {
            $chart=new Chart($text,"chart_fps_".$name,0,0);
            $chart->set_save_as_image(true);

            if(strcmp($type,"pie")==0)
            {
                $chart->set_pie("50%");
                $chart->set_tooltip('item','{a} <br/>{b} : {c} ({d}%)');
            }
            else
            {
                $chart->set_tooltip("axis",null);
                $chart->set_category('x',$ct_list);
            }

            $chart->add_data($name,$type,get_data_array($sql,$sql_table,$ct_list,$field,$where,$total,$sql_where));
            $chart->draw();
        }

        echo '</div>';
    }

    if(isset($_GET["mode"]))
        $mode=$_GET["mode"];
    else
        $mode="total";

    $nav=array( "total"         =>array("intro"=>"指定FPS",      "type"=>"bar",   "where"=>'S',               "text"=>"当前FPS有多少记录"),
                "trent"         =>array("intro"=>"趋势",         "type"=>"line",  "where"=>'>=',               "text"=>"大于等于指定帧数的记录数量"),
                "trent_desc"    =>array("intro"=>"逆趋势",       "type"=>"line",  "where"=>'<=',               "text"=>"小于等于指定帧数的记录数量"),
                "trent_per"     =>array("intro"=>"趋势百分比",    "type"=>"line",  "where"=>'>=%',              "text"=>"大于等于指定帧数的记录在总记录中的占比"),
                "trent_desc_per"=>array("intro"=>"逆趋势百分比",  "type"=>"line",  "where"=>'<=%',              "text"=>"小于等于指定帧数的记录在总记录中的占比"),
                "map5"          =>array("intro"=>"占比分布(5)",   "type"=>"pie",   "where"=>'5', "div"=>"5",  "text"=>"每0-5帧数在总记录中的占比"),
                "map10"         =>array("intro"=>"占比分布(10)",  "type"=>"pie",   "where"=>'10',"div"=>"10", "text"=>"每0-10帧数在总记录中的占比")
                );

    echo_html_header("GameLog");
        init_session();

        $sql_table=$_SESSION["Instance"];
        $sql_where=$_SESSION["Client"];

        $sb=get_sidebar("Frame");
        $sb->start();

            $div='';
            echo '<ul class="nav nav-pills">';
            foreach($nav as $key=>$item)
            {
                echo '<li role="presentation"';

                if(strcmp($mode,$key)==0)
                {
                    echo ' class="active"';
                    $type=$item["type"];
                    $intro=$item["intro"];
                    $where=$item["where"];
                    $text=$item["text"];

                    if(array_key_exists("div",$item))
                        $div='/'.$item["div"];
                }

                echo '><a href="chart_fps?mode='.$key.'">'.$item["intro"].'</a></li>';
            }
            echo '</ul>';

            init_sql_config();

            $sql=get_sql();

            $ct_list=sql_get_field_distinct($sql,$sql_table,"FLOOR(FrameRate".$div.")",$sql_where);
            $mct_list=sql_get_field_distinct($sql,$sql_table,"FLOOR(MaxFrameRate".$div.")",$sql_where);

            $total=sql_get_record_count($sql,$sql_table,$sql_where);

            if(count($ct_list)>1||count($mct_list)>1)
            {
                echo '<div class="row">';

                draw_chart("cur",$text,$type,$ct_list,$sql,$sql_table,"FrameRate",$where,$total,$sql_where);
                draw_chart("max",$text,$type,$mct_list,$sql,$sql_table,"MaxFrameRate",$where,$total,$sql_where);

                echo '</div>';
            }
            else
            {
                echo '没有数据';
            }

        $sb->end();
    echo_html_end();

