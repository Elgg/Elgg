<?php

/**
 * Elgg notifications personal subscription form
 * 
 * @package ElggNotifications
 */


// Echo title
echo elgg_view_title(elgg_echo('notifications:subscriptions:changesettings'));
		
echo elgg_view('subscriptions/form/additions', $vars);
		
// Display a description
?>
<div class="contentWrapper">
<div class="notification_methods">
<?php

echo elgg_view('input/form',array(
						'body' => 	elgg_view('notifications/subscriptions/personal') .
									elgg_view('notifications/subscriptions/collections') .
									elgg_view('notifications/subscriptions/forminternals'),
						'method' => 'post',
						'action' => $vars['url'] . 'action/notificationsettings/save',
				));

?>
</div>
</div>