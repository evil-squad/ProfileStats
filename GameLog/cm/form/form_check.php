<?php

    function create_check($name,$value,$text,$checked)
    {
        echo '<input type="checkbox" name="'.$name.'" value="'.$value.'"';

        if($checked=="true")
            echo ' checked';

        echo '> '.$text;
    }
