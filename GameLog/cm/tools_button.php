<?php

    require_once "form/form.php";
    require_once "form/form_button.php";

    function create_form_button($form_name,$link,$type,$value_list,$button_text,$button_style)
    {
        $ui=new UIForm($form_name,$type,$link);

        $ui->start();
            foreach($value_list as $key=>$value)
                $ui->add_hidden_value($key,$value);

        $ui->submit_end($button_text);
    }