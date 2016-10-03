<?php
/**
 * Dispatches a bulk action to real action.
 *
 * @package Elgg.Core.Plugin
 * @subpackage UserValidationByEmail
 */

$action_type = get_input('action_type');
$valid_actions = array('delete', 'resend_validation', 'validate');

if (!in_array($action_type, $valid_actions)) {
	forward(REFERRER);
}

$action_name = "uservalidationbyemail/$action_type";

action($action_name);