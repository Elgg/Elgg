<?php

	require("../includes.php");
	
	header("Content-type: text/css");

	$template_id = (int) $_REQUEST['template'];
		
	echo run("templates:draw",array(
			'template' => $template_id,
			'context' => 'css'
		)
		);

?>