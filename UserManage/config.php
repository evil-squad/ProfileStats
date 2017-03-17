<?php

    require_once "cm/ui_sidebar.php";

    function init_sql_config()
    {
        $_SESSION["DB_ADDRESS"  ]="localhost";
        $_SESSION["DB_USERNAME" ]="root";
        $_SESSION["DB_PASSWORD" ]="123456789";
        $_SESSION["DB_NAME"     ]="GameLog";
    }

    function sb_add($sb,$page,$table_name,$chart,$field,$intro)
    {
        $sb->add($page,get_icon_html($chart.'-chart').$table_name,'chart_'.$chart.'.php?sb='.$page.'&field='.$field.'&table_name='.$table_name.'&name='.$intro);
    }

    function get_sidebar($active)
    {
//         if(isset($_SESSION["UISideBar"]))
//         {
//             $sb=$_SESSION["UISideBar"];
//
//             $sb->set_active($active);
//             return($sb);
//         }

        $sb=new UISideBar($active,2);

        $sb->add("First","首页","index.php");
//        sb_add($sb,"Client",          "项目",           "bar",        "Client",               "当前项目的玩家数量");
        sb_add($sb,"ClientType",      "客户端类型",      "bar",        "Player",               "当前客户端类型的玩家数量");
        sb_add($sb,"ScreenSize",      "分辨率",          "bar",        "ScreenSize",          "当前分辨率的玩家数量");
        $sb->add("Frame","帧率","chart_fps.php");
        sb_add($sb,"RunningTime",     "游戏时间分布",     "line",     "FLOOR(RunningTime)",  "大于等于此时间(分钟)的玩家数量");
        sb_add($sb,"FlashVersion",    "Flash版本",      "bar",        "Version",              "玩家数量");
        sb_add($sb,"Renderer",        "渲染器",         "bar",        "Renderer",             "玩家数量");
        sb_add($sb,"glRenderer",      "OpenGL",        "bar",        "glRenderer",           "玩家数量");
        sb_add($sb,"Profile",         "Profile",       "bar",        "Profile",              "玩家数量");
        sb_add($sb,"Driver",          "驱动类型",       "bar",        "Driver",               "玩家数量");
        sb_add($sb,"System",          "操作系统",       "bar",        "System",               "玩家数量");
        sb_add($sb,"UserAgent",       "浏览器",         "bar",        "UserAgent",            "玩家数量");
        sb_add($sb,"Vendor",          "生产商",         "pie",        "Vendor",               "玩家数量");
        sb_add($sb,"CpuCore",         "Cpu核心数量",    "bar",        "CpuCore",              "玩家数量");
        sb_add($sb,"Country",         "玩家国家分布",    "pie",       "IP_Country",           "玩家数量");
        sb_add($sb,"MapRegion",       "中国玩家省份分布", "map",       "IP_Region",            "玩家数量");
        sb_add($sb,"MapCity",         "中国玩家城市分布", "bar",       "IP_City",              "玩家数量");

//         $_SESSION["UISideBar"]=$sb;
        return $sb;
    }
