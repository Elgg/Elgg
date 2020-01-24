<?php
/**
* Profile widgets/tools
*/

$modules = elgg_view('groups/profile/modules', $vars);

echo elgg_format_element('div', [
	'id' => 'groups-tools',
], $modules);
