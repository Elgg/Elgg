<?php
/**
 * A standard logout link
 *
 * Called within the Elgg topbar view.
 */

echo '<div class="log_out">';
echo elgg_view('output/url', array(
	'href' => "action/logout",
	'text' => elgg_echo('logout'),
	'is_action' => TRUE
));
echo '</div>';
