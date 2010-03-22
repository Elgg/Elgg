<?php
/**
 * Elgg profile icon avatar menu: Add / Remove friend links
 * 
 * @package ElggProfile
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Curverider Ltd <info@elgg.com>
 * @copyright Curverider Ltd 2008-2010
 * @link http://elgg.com/
 * 
 * @uses $vars['entity'] The user entity. If none specified, the current user is assumed. 
 */
$ts = time();
$token = generate_action_token($ts);
if ($vars['entity']->isFriend()) {
	echo elgg_view('output/confirmlink', array(
		'href' => "{$vars['url']}action/friends/remove?friend={$vars['entity']->getGUID()}",
		'text' => elgg_echo('friend:remove'),
		'class' => 'remove_friend'
	));
} else {
	echo elgg_view('output/confirmlink', array(
		'href' => "{$vars['url']}action/friends/add?friend={$vars['entity']->getGUID()}",
		'text' => elgg_echo('friend:add'),
		'class' => 'add_friend'
	));
}