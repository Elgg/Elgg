<?php
/**
 * Elgg user search box.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

echo elgg_view_title(elgg_echo('admin:users'));

if( (is_plugin_enabled('search')) && (is_plugin_enabled('profile')) ) {
	$header = elgg_echo('admin:user:label:search');
	$input = elgg_view('input/userpicker', array('internalname' => 'q'));

	echo <<<__HTML
<div class="admin_settings user_search">
	<h3>$header</h3>
	$input
</div>
__HTML;
}
