<?php

	/**
	 * This view will display featured groups - these are set by admin
	 **/
	 
	
?>

<h3><?php echo elgg_echo("groups:featured"); ?></h3>

<?php
	if($vars['featured']){
		
		foreach($vars['featured'] as $group){
			$icon = elgg_view(
				"groups/icon", array(
									'entity' => $group,
									'size' => 'small',
								  )
				);
				
			echo "<div class=\"featured_group\">" . $icon . " " . $group->name . "<br />";
			echo $group->briefdescription . "</div>";
			
		}
	}
?>