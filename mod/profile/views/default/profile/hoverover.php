<?php

	/**
	 * Elgg profile icon hover over
	 * 
	 * @package ElggProfile
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

?>

	<p class="user_menu_name">
		<b><?php echo $vars['entity']->name; ?></b>
	</p>

<?php

	echo elgg_view("profile/hoverover/actions",$vars);
	echo elgg_view("profile/hoverover/links",$vars);

?>