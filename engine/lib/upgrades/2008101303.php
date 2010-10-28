<?php

// Upgrade to solve login issue

if ($users = get_entities_from_metadata('validated_email', '', 'user', '', 0, 9999)) {
	foreach ($users as $user) {
		set_user_validation_status($user->guid, true, 'email');
	}
}
