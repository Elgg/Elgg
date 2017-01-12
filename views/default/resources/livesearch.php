<?php

/**
 * Page handler for autocomplete endpoint
 *
 * /livesearch/<match_on>?q=<query>
 */

$match_on = elgg_extract('match_on', $vars, get_input('match_on'));

if (!elgg_view_exists("resources/livesearch/$match_on")) {
	throw new \Elgg\PageNotFoundException();
}

elgg_set_viewtype('json');
echo elgg_view("resources/livesearch/$match_on", $vars);
