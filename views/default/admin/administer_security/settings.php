<?php

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:administer_security:settings:description'),
]);

echo elgg_view_form('admin/security/settings');
