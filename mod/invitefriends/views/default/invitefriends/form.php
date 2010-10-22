<?php
/**
 * Elgg invite form wrapper
 *
 * @package ElggInviteFriends
 */

echo elgg_view('input/form', array(
								'action' => $vars['url'] . 'action/invitefriends/invite',
								'body' => elgg_view('invitefriends/formitems'),
								'method' => 'post'
								)
);
