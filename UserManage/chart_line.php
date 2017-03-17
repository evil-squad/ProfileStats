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

        $sb=get_sidebar($sb_page);
        $sb->start();

            init_sql_config();

            $sql=get_sql();

            $ct_list=sql_get_field_distinct($sql,$sql_table,$field,$sql_where);

            if(count($ct_list)>1)
            {
                $ct_count=array();

                if($sql_where)
                {
                    foreach($ct_list as $ct)
                    {
                        if(strlen($ct)<=0)continue;
                        $ct_count[$ct]=sql_get_field_count($sql,$sql_table,$field,$field.">=".$ct.' AND '.$sql_where);
                    }
                }
                else
                {
                    foreach($ct_list as $ct)
                    {
                        if(strlen($ct)<=0)continue;
                        $ct_count[$ct]=sql_get_field_count($sql,$sql_table,$field,$field.">=".$ct);
                    }
                }

                $chart=new Chart($table_name,"chart_line",0,0);
                $chart->set_save_as_image(true);
                $chart->set_tooltip("axis",null);
                $chart->set_category('x',$ct_list);

                $cd=$chart->add_data($name,"line",$ct_count);
                $cd->area_style="normal:{}";

                $chart->draw();
            }
            else
            {
                echo '没有数据';
            }

        $sb->end();
    echo_html_end();
