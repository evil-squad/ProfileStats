<?php

    function load_obj_from_jsonfile($filename)
    {
        $file=file_get_contents($filename);

        if($file)
        {
            $result=json_decode($file,true);

            if($result)
            {
                return $result;
            }

            print_r(json_last_error_msg());
        }
        else
        {
            print_r("file_get_contents error");
        }

        exit;
    }


