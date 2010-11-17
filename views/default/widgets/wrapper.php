<?php
/**
 * Elgg widget wrapper
 *
 * @package Elgg
 * @subpackage Core
 */

$widgettypes = get_widget_types();

if ($vars['entity'] instanceof ElggObject && $vars['entity']->getSubtype() == 'widget') {
	$handler = $vars['entity']->handler;
	$title = $widgettypes[$vars['entity']->handler]->name;
	if (!$title) {
		$title = $handler;
	}
} else {
	$handler = "error";
	$title = elgg_echo("error");
}

?>
<div class="widget draggable">
	<div class="widget_title drag_handle">
		<h3>Widget Title</h3>
    </div>
    <div class="widget_content">
		<p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
	</div>
</div>
