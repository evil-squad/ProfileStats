<?php

    function get_localtime()
    {
        date_default_timezone_set('Asia/Shanghai');

        return time();
    }

    function time_mysql_format($timestamp)
    {
        return date("Y-m-d H:i:s",$timestamp);
    }

    function localtime_mysql_format()
    {
        return time_mysql_format(get_localtime());
    }
