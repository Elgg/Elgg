<?php

	/**
	 * Elgg profile links
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Dave Tosh <dave@elgg.com>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

?>

<?php

	echo elgg_view("profile/hoverover/actions",$vars);
	echo elgg_view("profile/hoverover/links",$vars);

?>