<?php

	function echo_button($link,$name)
	{
		echo '<a class="btn btn-primary" href="'.$link.'" onclick="';
		echo "ga('send', 'event', 'Homepage', 'Demo'";
		echo ');">'.$name.'</a>';
	}

	function echo_field_start($name)
	{
		echo '<fieldset><legend>'.$name.'</legend>';
	}

	function echo_field_end()
	{
		echo '</fieldset>';
	}
?>
