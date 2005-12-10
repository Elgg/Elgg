<?php

	// Draws the page, given a title and a main body (parameters[0] and [1]).

		global $messages;
	
		$messageshell = "";
		if (isset($messages) && sizeof($messages) > 0) {
			foreach($messages as $message) {
				$messageshell .= run("templates:draw", array(
										'context' => 'messages',
										'message' => $message
									)
									);
			}
			$messageshell = run("templates:draw", array(
									'context' => 'messageshell',
									'messages' => $messageshell
								)
								);
		}
	
		$run_result .= run("templates:draw",array(
							'context' => 'pageshell',
							'title' => $parameter[0],
							'menu' => run("display:menus:main"),
							'submenu' => run("display:menus:sub"),
							'top' => run("display:menus:top"),
							'sidebar' => run("display:sidebar"),
							'mainbody' => $parameter[1],
							'messageshell' => $messageshell
					)
					);
					
		global $querynum;
		global $querycache;
		
		$run_result .= "<!-- $querynum -->";
					
?>