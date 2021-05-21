<?php

echo elgg_view_page('', [
	'content' => elgg_view('core/account/login_box', ['title' => false]),
	'title' => elgg_echo('login'),
	'sidebar' => false,
	'filter' => false,
], 'walled_garden');
