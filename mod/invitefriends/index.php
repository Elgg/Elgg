<?php

/**
 * Elgg invite page
 *
 * @package ElggInviteFriends
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

gatekeeper();

set_context('friends');
set_page_owner(get_loggedin_userid());

$body = elgg_view('invitefriends/form');
$body = elgg_view_layout('two_column_left_sidebar', '', $body);

page_draw(elgg_echo('friends:invite'), $body);
