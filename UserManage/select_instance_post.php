<?php

    require_once "cm/phptools.php";
    require_once "config.php";

    echo_html_header("GameLog");
        init_session();

    init_sql_config();
    $sql=get_sql();

    $instance_name=$_POST["Instance"];

    echo '<center>';

    echo '<p>您选择了“'.$instance_name.'”样本</p>';

    $table_name='log_'.$instance_name;
    $_SESSION["Instance"]=$table_name;

    $count=sql_get_record_count($sql,$table_name,null);

    echo '<p>其中包括'.$count.'条数据，请选择一种数据分类</p>';

    echo '<p>';
    echo_button_link("全部数据，总计".$count.'条',"primary","select_client.php?where=all");
    echo '</p>';

    $ct_list=sql_get_field_distinct($sql,$table_name,"Client",null);

    foreach($ct_list as $ct)
    {
        $count=sql_get_field_count($sql,$table_name,"Client","Client like '".$ct."'");

        echo '<p>';
        echo_button_link($ct.'，总计'.$count.'条',"default","select_client.php?Client=".$ct);
        echo '</p>';
    }

    echo '</center>';
