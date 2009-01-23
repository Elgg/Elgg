<?php

	/**
	 * Elgg profile icon hover over
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
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