<?php
/**
 * @depreated 3.0 Use input/pages/parent
 */

elgg_deprecated_notice('
	"pages/input/parent" view has been deprecated and will be removed.
	Use "input/pages/parent" view instead.
', '3.0');

echo elgg_view('input/pages/parent', $vars);
