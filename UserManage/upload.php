<?php

    require_once "cm/phptools.php";
    require_once "config.php";

    function show_2col($first,$second)
    {
        echo '<div class="row">';
            echo '<div class="col-sm-4" style="text-align:right">'.$first.'</div>';
            echo '<div class="col-sm-4">'.$second.'</div>';
        echo '</div>';
    }

    init_session();
    init_sql_config();

    echo '名称：'.$_POST["instance_name"].'<br/>';

    echo '文件：'.$_FILES["instance"]["name"].'<br/>';
    echo '长度：'.$_FILES["instance"]["size"].'<br/>';

    if($_FILES["instance"]["error"]>0)
    {
        echo '加载错误：'.$_FILES["instance"]["size"].'<br/>';
        return;
    }

    $fp=fopen($_FILES["instance"]["tmp_name"],"r");

    if(!$fp)
    {
        echo 'openfile error';
        return;
    }

    echo 'openfile ok<br/>';

    $field_list=array(  "Date"          =>"Date",
                        "version"       =>"Version",
                        "frameRate"     =>"FrameRate",
                        "maxFrameRate"  =>"MaxFrameRate",
                        "logCount"      =>"LogCount",
                        "runningTime"   =>"RunningTime",
                        "profile"       =>"Profile",
                        "driver"        =>"Driver",
                        "player"        =>"Player",
                        "isDebugger"    =>"isDebugger",
                        "width"         =>"Width",
                        "height"        =>"Height",
                        "ip"            =>"IP",
                        "system"        =>"System",
                        "client"        =>"Client",
                        "renderer"      =>"Renderer",
                        "glRenderer"    =>"glRenderer",
                        "cpu core"      =>"CpuCore",
                        "userAgent"     =>"UserAgent",
                        "vendor"        =>"Vendor",
                        "extra"         =>"Extra");

    $first=false;

    $sql=get_sql();

    echo '正在创建数据库表格....<br/>';

    $sql->query("SET NAMES 'utf8'");
    $sql->query("USE gamelog");

    $sql_string="CREATE TABLE `log_".$_POST["instance_name"]."` (
                    `Date` datetime DEFAULT NULL,
                    `Version` char(32) COLLATE utf8_bin DEFAULT NULL,
                    `FrameRate` float DEFAULT NULL,
                    `MaxFrameRate` float DEFAULT NULL,
                    `LogCount` int(6) DEFAULT NULL,
                    `RunningTime` float DEFAULT NULL,
                    `Profile` char(32) COLLATE utf8_bin DEFAULT NULL,
                    `Driver` char(64) COLLATE utf8_bin DEFAULT NULL,
                    `Player` char(16) COLLATE utf8_bin DEFAULT NULL,
                    `isDebugger` tinyint(1) DEFAULT NULL,
                    `Width` int(4) DEFAULT NULL,
                    `Height` int(4) DEFAULT NULL,
                    `ScreenSize` char(16) COLLATE utf8_bin DEFAULT NULL,
                    `IP` char(20) COLLATE utf8_bin DEFAULT NULL,
                    `System` char(16) COLLATE utf8_bin DEFAULT NULL,
                    `Client` char(16) COLLATE utf8_bin DEFAULT NULL,
                    `Renderer` char(64) COLLATE utf8_bin DEFAULT NULL,
                    `glRenderer` char(80) COLLATE utf8_bin DEFAULT NULL,
                    `CpuCore` int(2) DEFAULT NULL,
                    `UserAgent` text COLLATE utf8_bin,
                    `Vendor` char(32) COLLATE utf8_bin DEFAULT NULL,
                    `Extra` char(8) COLLATE utf8_bin DEFAULT NULL,
                    `IP_Country` char(32) COLLATE utf8_bin DEFAULT NULL,
                    `IP_Area` char(8) COLLATE utf8_bin DEFAULT NULL,
                    `IP_Region` char(32) COLLATE utf8_bin DEFAULT NULL,
                    `IP_City` char(32) COLLATE utf8_bin DEFAULT NULL,
                    `IP_ISP` char(32) COLLATE utf8_bin DEFAULT NULL
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin;";

    $result=$sql->query($sql_string);

    if(!$result)
    {
        echo '创建数据库表格失败，SQLString: '.$sql_string;
        return;
    }

    $sql_string="";

    $width=0;
    $height=0;

    $ip_loc=array();

    echo '正在插入数据库,请稍候....<br/>';

    $count=0;

    while(!feof($fp))
    {
        $line=fgets($fp);

        if(strncmp($line,"LOGSTART",8)==0)
        {
            $sql_string="INSERT INTO log_".$_POST["instance_name"]." SET ";
            $first=true;
            continue;
        }

        if(strncmp($line,"LOGEND",6)==0)
        {
            $result=$sql->query($sql_string);

            if(!$result)
                echo 'insert failed,SQLString: '.$sql_string.'<br/>';

            ++$count;

            echo '.';

            if($count%10==0)
                $count;

            continue;
        }

        $gap=strchr($line,'=');

        $left=substr($line,0,strlen($line)-strlen($gap));
        $right=trim(substr($gap,1));

        if(strlen($right)==0)continue;

        if(strcmp($right,"null")==0)continue;

        if(strcmp($left,"Date")==0)     //日期转换
        {
            $year   =substr($right, 4,4);
            $month  =substr($right, 9,2);
            $day    =substr($right,12,2);

            $hour   =substr($right,18,2);
            $minute =substr($right,21,2);
            $second =substr($right,24,2);
            $p      =substr($right,27,1);

            if($p=='P')      //下午
            {
                if($hour!=12)
                    $hour+=12;
            }

            $right=$year.'-'.$month.'-'.$day.' '.$hour.':'.$minute.':'.$second;
        }

        if(strcmp($left,"isDebugger")==0)
        {
            if(strcmp($right,"true")==0)$right='1';else
            if(strcmp($right,"false")==0)$right='0';
        }

        if(strcmp($left,"cpu core")==0)
        {
            if(strcmp($right,"undefined")==0)
                $right='0';
        }

        if(strcmp($left,"runningTime")==0)
        {
            $right=strstr($right," Mins",true);
        }

        if(!$first)
            $sql_string.=',';

        $sql_string.=$field_list[$left].'="'.$right.'"';

        if(strcmp($left,"width")==0)$width=$right;
        if(strcmp($left,"height")==0)
        {
            $sql_string.=',ScreenSize="'.$width.'x'.$right.'"';      //增加一个Width x Height的数据
        }

//         if(strcmp($left,"ip")==0)
//         {
//             if(array_key_exists($right,$ip_loc))
//                 $loc=$ip_loc[$right];
//             else
//             {
//                 $loc=null;
//
//                 for($i=0;$i<10;$i++)
//                 {
//                     $loc=get_ip_local($right);
//
//                     if($loc!=null)
//                     {
//                         $ip_loc[$right]=$loc;
//                         break;
//                     }
//
//                     sleep(1);
//                 }
//             }
//
//             $sql_string.=',IP_Country="'.$loc["country"].'",
//                            IP_Area="'.$loc["area"].'",
//                            IP_Region="'.$loc["region"].'",
//                            IP_City="'.$loc["city"].'",
//                            IP_ISP="'.$loc["isp"].'"';
//         }

        $first=false;
    }

    fclose($fp);

    echo '<br/>插入完成，即将返回首页';

    echo_autogoto(3,"index.php");
