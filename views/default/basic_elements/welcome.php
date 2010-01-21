<?php
/**
 * Elgg sample welcome page
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

$user = $vars['name'];

?>

<p><?php echo sprintf(elgg_echo('welcome:user'), $user); ?></p>
<p><?php echo elgg_view('output/url', array('href' => "{$vars['url']}action/logout", 'text' => elgg_echo('logout'), 'is_action' => TRUE)); ?></p>

