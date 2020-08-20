<?php
/**
 * Elgg statistics screen
 */

$user = elgg_get_page_owner_entity();
if (!$user instanceof ElggUser) {
	return;
}

$entity_stats = get_entity_statistics($user->guid);
if (empty($entity_stats)) {
	return;
}

$rows = '';

foreach ($entity_stats as $k => $entry) {
	foreach ($entry as $a => $b) {
		if (elgg_language_key_exists("collection:{$k}:{$a}")) {
			$a = elgg_echo("collection:{$k}:{$a}");
		} else {
			$a = "$k $a";
		}
	
		$rows .= <<< END
			<tr>
				<td class="column-one"><b>{$a}:</b></td>
				<td>{$b}</td>
			</tr>
END;
	}
}

$content = "<table class=\"elgg-table-alt\">$rows</table>";

echo elgg_view_module('info', elgg_echo('usersettings:statistics:label:numentities'), $content);
