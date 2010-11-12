<?php

/**
 * Elgg invite page
 *
 * @package ElggInviteFriends
 */

require_once(dirname(dirname(dirname(__FILE__))) . '/engine/start.php');

gatekeeper();

elgg_set_context('friends');
set_page_owner(get_loggedin_userid());

$body = elgg_view('invitefriends/form');
$body = elgg_view_layout('one_column_with_sidebar', array('content' => $body));

echo elgg_view_page(elgg_echo('friends:invite'), $body);
