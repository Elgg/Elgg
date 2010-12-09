<?php
/**
 * A standard logout link
 *
 * Called within the Elgg topbar view.
 */


echo elgg_view('output/url', array(
	'href' => "action/logout",
	'text' => elgg_echo('logout'),
	'is_action' => TRUE,
	'class' => 'alt',
));
