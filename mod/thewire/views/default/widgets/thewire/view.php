
	<?php

		// Get any wire notes to display
		// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
		
		$num = $vars['entity']->num_display;
		if(!$num)
			$num = 4;
		
		$thewire = $page_owner->getObjects('thewire', $num);
		
		// If there are any thewire to view, view them
		if (is_array($thewire) && sizeof($thewire) > 0) {
			
			foreach($thewire as $shout) {
				
				echo elgg_view_entity($shout);
				
			}
			
		}
	
	?>
