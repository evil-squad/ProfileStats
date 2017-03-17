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

        $min=0;
        $max=0;
        $cur=0;
        $ct_count=array();
        foreach($ct_list as $ct)
        {
            if(strlen($ct)<=0)continue;

            if($sql_where)
                $cur=sql_get_field_count($sql,$sql_table,$field,$field." like '".$ct."' AND ".$sql_where);
            else
                $cur=sql_get_field_count($sql,$sql_table,$field,$field." like '".$ct."'");

            if(strlen(strchr($ct,'省'))>0)$ct=strstr($ct,'省',true);else
            if(strlen(strchr($ct,'市'))>0)$ct=strstr($ct,'市',true);else
            if(strlen(strchr($ct,'壮'))>0)$ct=strstr($ct,'壮',true);else
            if(strlen(strchr($ct,'维'))>0)$ct=strstr($ct,'维',true);else
            if(strlen(strchr($ct,'藏'))>0)$ct=strstr($ct,'藏',true);else
            if(strlen(strchr($ct,'回'))>0)$ct=strstr($ct,'回',true);else
            if(strlen(strchr($ct,'自'))>0)$ct=strstr($ct,'自',true);

            $ct_count[$ct]=$cur;

            if($cur<$min)$min=$cur;
            if($cur>$max)$max=$cur;
        }

        $sb=get_sidebar($sb_page);
        $sb->start();

            $chart=new Chart($table_name,"chart_map",0,0);
            $chart->set_save_as_image(true);
            $chart->set_visual_map($min,$max);
            $chart->set_map("china");

            $cd=$chart->add_data($name,"map",$ct_count);

            $chart->draw();

        $sb->end();
    echo_html_end();

