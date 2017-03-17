<?php

    require_once "cm/phptools.php";
    require_once "config.php";

    echo_html_header("GameLog");
        init_session();

    init_sql_config();
    $sql=get_sql();

    $instance_list=array();
    $first_instance=null;

    //现有样本查找
    {
        $sql_result=$sql->query("show tables");

        if($sql_result)
        {
            while($tab=$sql_result->fetch_row())
            {
                if(strncmp($tab[0],"log_",4))continue;       //必须是log开头

                $tab_name=substr($tab[0],4);
                $instance_list[$tab_name]=$tab_name;

                if(!$first_instance)
                    $first_instance=$tab_name;
            }
        }
    }

    echo '<div class="row">';

    if(count($instance_list)>0)
    {
        $form=new UIForm("select_instance","post","select_instance_post.php");

        $form->set_panel_title("选择一个已有样本：","panel panel-success");
        $form->start();

        create_select("请选择一个样本","Instance",$first_instance,$instance_list);

        $form->submit_end("确定使用此样本");
    }

    echo '</div>';
    echo '<div class="row">';

    //提交一个新的样本
    {
        $form=new UIForm("upload_instance","post","upload.php");

        $form->set_panel_title("提交一个新的样本","panel panel-info");
        $form->set_upload();

        $form->start();

        CreateInputGroup("text","instance_name","新的样本名称：",0,"不超过32个字符，仅可以使用字母和数字以及下划线");

        $form->add_file_upload("instance_file","instance");

        $form->submit_end("确认提交该数据样本");
    }
    echo '</div>';

    echo_html_end();
