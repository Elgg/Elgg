<?php
/**
 * Elgg personal notifications
 */

	
	echo elgg_view('subscriptions/form/additions',$vars);
	
// Display a description
?>
<div class="user-settings notifications">
<?php


echo elgg_view('input/form',array(
		'body' => 	elgg_view('notifications/subscriptions/personal') .
					elgg_view('notifications/subscriptions/collections') .
					elgg_view('notifications/subscriptions/forminternals'),
		'method' => 'post',
		'action' => 'action/notificationsettings/save',
	));

?>
</div>
