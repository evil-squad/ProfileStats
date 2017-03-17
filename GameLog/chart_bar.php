<?php

    require_once "cm/phptools.php";
    require_once "config.php";

    $sb_page=$_GET["sb"];
    $field=$_GET["field"];
    $table_name=$_GET["table_name"];
    $name=$_GET["name"];

    echo_html_header("GameLog");
        init_session();

        $sql_table=$_SESSION["Instance"];
        $sql_where=$_SESSION["Client"];

        init_sql_config();

        $sql=get_sql();

        $ct_list=sql_get_field_distinct($sql,$sql_table,$field,$sql_where);

        $ct_count=array();

        if($sql_where)
        {
            foreach($ct_list as $ct)
                $ct_count[$ct]=sql_get_field_count($sql,$sql_table,$field,$field." like '".$ct."' AND ".$sql_where);
        }
        else
        {
            foreach($ct_list as $ct)
                $ct_count[$ct]=sql_get_field_count($sql,$sql_table,$field,$field." like '".$ct."'");
        }

        $sb=get_sidebar($sb_page);
        $sb->start();

            echo_icon_link("pie-chart","饼形图",'chart_pie.php?sb='.$sb_page.'&field='.$field.'&table_name='.$table_name.'&name='.$name);

            $chart=new Chart($table_name,"chart_bar",0,0);
            $chart->set_save_as_image(true);
            $chart->set_category('y',$ct_list);

            $cd=$chart->add_data($name,"bar",$ct_count);

            $chart->draw();

        $sb->end();
    echo_html_end();