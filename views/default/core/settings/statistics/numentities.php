<?php
/**
 * Elgg statistics screen
 *
 * @package Elgg
 * @subpackage Core
 */

$user = elgg_get_page_owner_entity();
if (!$user instanceof ElggUser) {
	return;
}

$entity_stats = get_entity_statistics($user->guid);

if ($entity_stats) {
	$rows = '';
	
	foreach ($entity_stats as $k => $entry) {
		foreach ($entry as $a => $b) {
			if ($a == "__base__") {
				$a = elgg_echo("collection:{$k}");
				if (empty($a)) {
					$a = $k;
				}
			} else {
				$a = elgg_echo("collection:{$k}:{$a}");
				if (empty($a)) {
					$a = "$k $a";
				}
			}
			$rows .= <<< END
				<tr>
					<td class="column-one"><b>{$a}:</b></td>
					<td>{$b}</td>
				</tr>
END;
		}
	}

	$title = elgg_echo('usersettings:statistics:label:numentities');
	$content = "<table class=\"elgg-table-alt\">$rows</table>";

	echo elgg_view_module('info', $title, $content);
}
