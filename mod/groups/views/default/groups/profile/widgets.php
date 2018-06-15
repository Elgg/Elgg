<?php
/**
* Profile widgets/tools
*
* @package ElggGroups
*/

$modules = elgg_view('groups/profile/modules', $vars);

echo elgg_format_element('div', [
	'id' => 'groups-tools',
], $modules);
