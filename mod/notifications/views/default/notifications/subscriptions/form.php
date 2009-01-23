<?php

	/**
	 * Elgg notifications plugin settings form
	 * 
	 * @package ElggNotifications
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.com/
	 */

	// Get subscriptions
		$people = $vars['subscriptions'];
		if (empty($people) || !is_array($people))
			$people = array();
		
	// Echo title
		echo elgg_view_title(elgg_echo('notifications:subscriptions:changesettings'));

	// Display a description
?>
	<p>
		<?php echo elgg_echo('notifications:subscriptions:description'); ?>
	</p>
<?php

	// Get the friends picker and load it with our people subscriptions
		echo elgg_view('friends/picker',array(
									'internalname' => 'subscriptions',
									'entities' => get_user_friends($vars['user']->guid,'',9999,0),
									'value' => $people,
									'formtarget' => $vars['url'] . 'action/notificationsettings/save'
								));

?>