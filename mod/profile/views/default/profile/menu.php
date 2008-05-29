<?php

	/**
	 * Elgg profile menu
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity
	 */

		if (isloggedin() && $vars['entity']->getGUID() != $vars['user']->getGUID()) {
			
			if ($vars['entity']->isFriend()) {
				
				echo "<p><a href=\"{$vars['url']}action/friends/remove?friend={$vars['entity']->getGUID()}\">" . elgg_echo("friend:remove") . "</a></p>";
				
			} else {
				
				echo "<p><a href=\"{$vars['url']}action/friends/add?friend={$vars['entity']->getGUID()}\">" . elgg_echo("friend:add") . "</a></p>";
				
			}
			
			echo "<p>&nbsp;</p>";
			
		}

?>
	<p><a href="<?php echo $vars['url']; ?>pg/friends/<?php echo $vars['entity']->username ?>/"><?php echo sprintf(elgg_echo("friends:owned"),$vars['entity']->name) ?></a></p>
	<p><a href="<?php echo $vars['url']; ?>pg/friendsof/<?php echo $vars['entity']->username ?>/"><?php echo sprintf(elgg_echo("friends:of:owned"),$vars['entity']->name) ?></a></p>