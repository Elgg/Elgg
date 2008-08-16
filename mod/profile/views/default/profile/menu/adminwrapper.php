<?php

	$adminlinks = elgg_view('profile/menu/adminlinks',$vars);

	if (!empty($adminlinks)) {

		echo "<p class=\"user_menu_admin\">{$adminlinks}</p>";
		
	}
	
?>