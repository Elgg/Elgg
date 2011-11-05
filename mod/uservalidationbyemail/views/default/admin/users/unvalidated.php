<?php
/**
 * List of unvalidated users
 */

echo elgg_view_form('uservalidationbyemail/bulk_action', array(
	'id' => 'unvalidated-users',
	'action' => 'action/uservalidationbyemail/bulk_action'
));
