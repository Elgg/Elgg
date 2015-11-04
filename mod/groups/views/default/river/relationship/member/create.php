<?php
/**
 * Group join river view.
 */

echo elgg_view('river/elements/layout', array(
	'item' => $vars['item'],

	// truthy value to bypass responses rendering
	'responses' => ' ',
));
