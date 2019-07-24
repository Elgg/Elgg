<?php
/**
 * Avatar cropping view
 *
 * @uses vars['entity']
 */

elgg_deprecated_notice("The view 'core/avatar/crop' is deprecated use 'entity/edit/icon'", '3.1');

$content = elgg_view('output/longtext', [
	'value' => elgg_echo('avatar:create:instructions'),
]);

$content .= elgg_view_form('avatar/crop', [], $vars);

echo elgg_view_module('info', elgg_echo('avatar:crop:title'), $content, [
	'id' => 'avatar-croppingtool',
]);
