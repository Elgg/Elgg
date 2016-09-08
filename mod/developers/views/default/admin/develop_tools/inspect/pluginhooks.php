<?php
/**
 * WARNING! This view is internal and may change at any time.
 * Plugins should not use/modify/override this view.
 */

echo elgg_view('admin/develop_tools/inspect/events', array(
	'data' => elgg_extract("data", $vars),
	'header' => elgg_echo('developers:inspect:pluginhooks'),
));
