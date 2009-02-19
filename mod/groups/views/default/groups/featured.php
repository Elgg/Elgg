<?php

	/**
	 * This view will display featured groups - these are set by admin
	 **/
	 
	
?>
<div class="sidebarBox featuredgroups">
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
				
			echo "<div class=\"contentWrapper\">" . $icon . " <p><span>" . $group->name . "</span><br />";
			echo $group->briefdescription . "</p><div class=\"clearfloat\"></div></div>";
			
		}
	}
?>
</div>