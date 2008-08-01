<?php

		gatekeeper();
		
		$closed = get_input('closed','true');
		if ($closed != 'true') {
			$closed = false;
		} else {
			$closed = true;
		}
		
		$_SESSION['user']->spotlightclosed = $closed;
		exit;

?>