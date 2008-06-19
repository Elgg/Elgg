<?php

	/**
	 * Elgg profile icon hover over
	 * 
	 * @package ElggProfile
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Ben Werdmuller <ben@curverider.co.uk>
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 * 
	 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
	 */

?>

	<p class="user_menu_name">
		<b><?php echo $vars['entity']->name; ?></b>
	</p>

<?php

	if (isloggedin()) {
		if ($_SESSION['user']->getGUID() != $vars['entity']->getGUID()) {
			if ($vars['entity']->isFriend()) {
				echo "<p class=\"user_menu_removefriend\"><a href=\"{$vars['url']}action/friends/remove?friend={$vars['entity']->getGUID()}\">" . elgg_echo("friend:remove") . "</a></p>";
			} else {
				echo "<p><a href=\"{$vars['url']}action/friends/add?friend={$vars['entity']->getGUID()}\">" . elgg_echo("friend:add") . "</a></p>";
			}
		}
	}

?>

	<p class="user_menu_profile">
		<a href="<?php echo $vars['entity']->getURL(); ?>"><?php echo elgg_echo("profile"); ?></a>
	</p>
	<p class="user_menu_friends">
		<a href="<?php echo $vars['url']; ?>pg/friends/bwerdmuller/"><?php echo elgg_echo("friends"); ?></a>	
	</p>
	<p class="user_menu_friends_of">
		<a href="<?php echo $vars['url']; ?>pg/friendsof/bwerdmuller/"><?php echo elgg_echo("friends:of"); ?></a>	
	</p>