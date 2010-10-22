<?php

/**
 * Elgg Friends widget
 * This plugin allows users to put a list of their friends on their profile
 *
 * @package ElggFriends
 */

function friends_init() {
	add_widget_type('friends', elgg_echo("friends"), elgg_echo('friends:widget:description'));
}

register_elgg_event_handler('init', 'system', 'friends_init');
