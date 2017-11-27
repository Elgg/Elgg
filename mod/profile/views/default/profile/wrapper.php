<?php
/**
 * Profile info box
 *
 * @uses $vars['entity'] The user entity
 */

$user = elgg_extract('entity', $vars);
if ($user->isBanned()) {
	$reason = ($user->ban_reason === 'banned') ? '' : $user->ban_reason;
	
	echo elgg_view_message('warning', $reason, [
		'title' => elgg_echo('banned'),
	]);
}

?>
<div class="profile">
	<div class="elgg-inner clearfix h-card vcard">
		<?php echo elgg_view('profile/owner_block', $vars); ?>
		<?php echo elgg_view('profile/details', $vars); ?>
	</div>
</div>
