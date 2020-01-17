<?php

use Elgg\EntityPermissionsException;
use Elgg\GatekeeperException;

if (!elgg_get_config('allow_registration')) {
	throw new GatekeeperException(elgg_echo('invitefriends:registration_disabled'));
}

$page_owner = elgg_get_page_owner_entity();
if (!$page_owner instanceof ElggUser || $page_owner->guid !== elgg_get_logged_in_user_guid()) {
	throw new EntityPermissionsException();
}

echo elgg_view_page(elgg_echo('friends:invite'), [
	'content' => elgg_view_form('friends/invite', ['prevent_double_submit' => true]),
	'show_owner_block_menu' => false,
]);
