<?php

echo elgg_view('output/longtext', [
	'value' => elgg_echo('admin:security:settings:description'),
]);

echo elgg_view_form('admin/security/settings');
