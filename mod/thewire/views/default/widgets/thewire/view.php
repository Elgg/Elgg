<p>
	<?php

		// Get any wire notes to display
		// Get the current page's owner
		$page_owner = page_owner_entity();
		if ($page_owner === false || is_null($page_owner)) {
			$page_owner = $_SESSION['user'];
			set_page_owner($page_owner->getGUID());
		}
		
		$thewire = $page_owner->getObjects('thewire', $vars['entity']->num_display);
		
		// If there are any thewire to view, view them
		if (is_array($thewire) && sizeof($thewire) > 0) {
			
			foreach($thewire as $shout) {
				
				echo elgg_view_entity($shout);
				
			}
			
		}
	
	?>
</p>