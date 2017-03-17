<?php

    function include_jquery()
    {
        echo '<script src="3rdpty/jquery-3.1.0.js"></script>';
    }

    function include_font_awesome()
    {
        echo '<link rel="stylesheet" href="3rdpty/font-awesome/css/font-awesome.min.css">';
    }

    function include_bootstrap()
    {
        echo '<link rel="stylesheet" href="3rdpty/bootstrap/css/bootstrap.css">';
        echo '<link rel="stylesheet" href="3rdpty/bootstrap/css/bootstrap-theme.css">';
        echo '<link rel="stylesheet" href="3rdpty/sb-admin-2/css/sb-admin-2.css">';
        echo '<script src="3rdpty/sb-admin-2/js/sb-admin-2.js"></script>';
        echo '<script src="3rdpty/bootstrap/js/bootstrap.js"></script>';
    }

    function include_echarts()
    {
        echo '<script src="3rdpty/echarts.js"></script>';
        echo '<script src="3rdpty/china.js"></script>';
    }

    function echo_html_header($title)
    {
        echo '<html>
                <head>
                    <title>'.$title.'</title>
                    <meta charset="utf-8">
                    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                    <meta name="viewport" content="initial-scale=1.0, user-scalable=yes, minimum-scale=0.25, maximum-scale=5.0, width=device-width" />';

        include_jquery();
        include_font_awesome();
        include_bootstrap();
        include_echarts();

        echo    '</head><body>';
    }

    function echo_page_header($header,$subtext)
    {
        echo '<div class="page-header" style="margin-bottom: 10px; padding-bottom: 0px; margin-top: 0px;"><h1 style="margin-top: 10px;">'.$header.'<small> '.$subtext.'</small></h1></div>';
    }

    function echo_html_end()
    {
        echo '</body></html>';
    }

    function echo_div($id)
    {
        echo '<div id="'.$id.'"></div>';
    }

    function echo_hr()
    {
    	echo '<hr size="1"/>';
    }

    function echo_include_script($filename)
    {
		echo '<script src="'.$filename.'"></script>';
    }

    function echo_autogoto($time,$page)
    {
        echo '<meta http-equiv="refresh" content="'.$time.';url='.$page.'">';
    }

    function echo_link($text,$link)
    {
        echo '<a href="'.$link.'">'.$text.'</a>';
    }

    function echo_span_glyph($glyph)
    {
    	echo '<span class="glyphicon glyphicon-'.$glyph.'"></span>';
    }

    function echo_span_hint_link($glyph,$text,$hint,$link)
    {
    	echo '<a href="'.$link.'" data-toggle="tooltip" data-placement="top" title="'.$hint.'">'.'<span class="glyphicon glyphicon-'.$glyph.'"></span>'.$text.'</a>';
    }

    function echo_icon_hint_link($icon,$text,$hint,$link)
    {
    	echo '<a href="'.$link.'" data-toggle="tooltip" data-placement="top" title="'.$hint.'">';
        echo_icon($icon);
        echo $text.'</a>';
    }

    function echo_icon_link($icon,$text,$link)
    {
    	echo '<a href="'.$link.'" data-placement="top">';
        echo_icon($icon);
        echo $text.'</a>';
    }

    function get_span_label_html($style,$text)
    {
        return '<span class="label label-'.$style.'">'.$text.'</span>';
    }

    function echo_span_label($style,$text)
    {
    	echo get_span_label_html($style,$text);
    }

    function get_icon_html($name)
    {
        return '<i class="fa fa-'.$name.'" aria-hidden="true"></i>';
    }

    function echo_icon($name)
    {
        echo get_icon_html($name);
    }

    function get_badge_html()
    {
        $text=func_get_arg(0);

        if(func_num_args()>1)
            $style="badge badge-".func_get_arg(1);
        else
            $style="badge";

        return '<span class="'.$style.'">'.$text.'</span>';
    }

    function echo_badge($text)
    {
        echo get_badge_html($text);
    }

    function echo_alert($style,$text)
    {
    	echo '<div class="alert alert-'.$style.'" role="alert">'.$text.'</div>';
    }

    function echo_button_link($text,$style,$link)
    {
        echo '<a href="'.$link.'" class="btn btn-'.$style.'" role="button">'.$text.'</a>';
    }
?>
