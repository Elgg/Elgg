<?php

	// Substitute variables in templates:
	// where {{variablename}} is found in the template, this function is passed
	// "variablename" and returns the proper variable

		global $menubar;
		global $metatags;
		
		$variables = $parameter[0];
		$template_variable = $parameter[1];
				
		if (isset($variables[$template_variable])) {
			$run_result = $variables[$template_variable];
		} else {
			switch($template_variable) {
				
				case "menubar":			$run_result = $menubar;
										break;
				case "url":				$run_result = url;
										break;
				case "userfullname":	global $page_owner;
										if (!isset($page_owner) || $page_owner == -1) {
											$run_result = "";
										} else {
											$run_result = run("users:id_to_name", $page_owner);
										}
										break;
				case "metatags":
										// $run_result = "<link href=\"/".$parameter[2].".css\" rel=\"stylesheet\" type=\"text/css\" />";
										$run_result = "<style><!--\n";
										$run_result .= run("templates:draw",array(
																				'template' => $template_id,
																				'context' => 'css'
																				)
																				);
										$run_result .= "// -->\n</style>\n";
										$run_result .= $metatags;
										break;
				
			}
		}

?>