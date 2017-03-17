<?php

	function init_session()
	{
		if (!isset($_SESSION))
			session_start();
		
		if (!isset($_SESSION['id']))
			return ;
		
		$now = time();
		
		if(($now-$_SESSION['session_time'])>1200)			//超时时间,单位:秒,这里设为20分钟
		{
			unset($_SESSION['id']);						//超时了.
			return;
		}
		
		$_SESSION['session_time']=$now;				//还没超时
	}
	
	function clear_session()
	{		
		session_destroy();
	}
?>