<?php
	/**
	 * Elgg Pages: Add group menu
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
?>
<p class="user_menu_pages">
		<a href="<?php echo $vars['url']; ?>pg/pages/owned/<?php echo $vars['entity']->username; ?>"><?php echo elgg_echo("pages"); ?></a>	
	</p>