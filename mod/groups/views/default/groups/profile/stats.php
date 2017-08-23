<?php

$group = elgg_extract('entity', $vars);
if (!($group instanceof \ElggGroup)) {
	return;
}

$owner = $group->getOwnerEntity();
if (!($owner instanceof ElggEntity)) {
	// not having an owner is very bad so we throw an exception
	$msg = "Sorry, 'group owner' does not exist for guid:{$group->guid}";
	throw new InvalidParameterException($msg);
}

?>
<p>
	<b><?= elgg_echo("groups:owner"); ?>: </b>
	<?php
		echo elgg_view('output/url', [
			'text' => $owner->name,
			'value' => $owner->getURL(),
			'is_trusted' => true,
		]);
	?>
</p>
<p>
	<b><?= elgg_echo("groups:members"); ?>: </b>
	<?= $group->getMembers(['count' => true]); ?>
</p>
<?php

// membership status
$user = elgg_get_logged_in_user_entity();
if ($user && $group->isMember($user)) {
	echo '<p>' . elgg_echo('groups:my_status:group_member') . '</p>';
}
