<?php

// added in "complete" step of the installer
elgg_delete_admin_notice('fresh_install');

echo elgg_view_form('admin/site/settings', [
	'class' => 'elgg-form-settings',
]);
