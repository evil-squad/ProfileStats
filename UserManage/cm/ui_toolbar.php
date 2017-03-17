<?php

	class UIToolBar
	{
		private $label="";

		function __construct($lab)
		{
			$this->label=$lab;
		}

		function start()
		{
			echo '<div id="'.$this->label.'" class="toolbar">';
		}

		function end()
		{
			echo "</div>
					<script>
						YUI().use(
						  'aui-toolbar',
						  function(Y) {
						    new Y.Toolbar(
						      {
						        boundingBox: '#".$this->label."'
						      }
						    ).render();
						  }
						);
	        		</script>";
		}
	};//class UIToolBar
?>
