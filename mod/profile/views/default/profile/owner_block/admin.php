<?php

$admin = elgg_extract('admin', $vars);
/* @var ElggMenuItem[] $admin */

if (!elgg_is_admin_logged_in() || elgg_get_logged_in_user_guid() == elgg_get_page_owner_guid()) {
	return;
}

$text = elgg_echo('admin:options');
$lis = [];

foreach ($admin as $menu_item) {
	$lis[] = elgg_view('navigation/menu/elements/item', [
		'item' => $menu_item,
	]);
}

?>
<ul class="profile-admin-menu-wrapper">
	<li><a rel="toggle" href="#profile-menu-admin"><?= $text ?>&hellip;</a>
		<ul class="profile-admin-menu" id="profile-menu-admin">
			<?= implode('', $lis) ?>
		</ul>
	</li>
</ul>
