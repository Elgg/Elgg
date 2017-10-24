<?php
/**
 * Dispatches a bulk action to real action.
 */

$action_type = get_input('action_type');
$valid_actions = ['delete', 'resend_validation', 'validate'];

if (!in_array($action_type, $valid_actions)) {
	return elgg_error_response();
}

action("uservalidationbyemail/{$action_type}");
