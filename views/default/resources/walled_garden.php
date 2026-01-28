<?php

echo elgg_view_page(elgg_echo('login'), [
	'content' => elgg_view('core/account/login_box', ['title' => false]),
	'sidebar' => false,
	'filter' => false,
], 'walled_garden');
