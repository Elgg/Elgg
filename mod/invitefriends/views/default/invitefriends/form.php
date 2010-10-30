<?php
/**
 * Elgg invite form wrapper
 *
 * @package ElggInviteFriends
 */

echo elgg_view('input/form', array(
								'action' => elgg_get_site_url() . 'action/invitefriends/invite',
								'body' => elgg_view('invitefriends/formitems'),
								'method' => 'post'
								)
);
