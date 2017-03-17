<?php

    require_once "cm/phptools.php";
    require_once "config.php";

    init_session();

    $client=$_GET["Client"];

    $_SESSION["Client"]='isDebugger=0';

    if(strcmp($client,"all"))
        $_SESSION["Client"].=' AND Client like "'.$client.'"';

    echo_autogoto(0,"http://localhost/GameLog/chart_bar.php?sb=ClientType&field=Player&table_name=%E5%AE%A2%E6%88%B7%E7%AB%AF%E7%B1%BB%E5%9E%8B&name=%E5%BD%93%E5%89%8D%E5%AE%A2%E6%88%B7%E7%AB%AF%E7%B1%BB%E5%9E%8B%E7%9A%84%E7%8E%A9%E5%AE%B6%E6%95%B0%E9%87%8F");
