<?php
/**
* Profile widgets/tools
*
* @package ElggGroups
*/

$views = _elgg_services()->views->getViewList('groups/tool_latest');

$col1 = [];
$col2 = [];
$i = 0;
foreach ($views as $view) {
	if ($view == 'groups/tool_latest') {
		continue;
	}

	$output = elgg_view($view, $vars);
	if ($output) {
		$i++;
		if ($i % 2 == 1) {
			$col1[] = $output;
		} else {
			$col2[] = $output;
		}
	}
}

?>
<div class="groups-tools row">
	<div class="groups-tools-col col-12 col-md-6">
		<?= implode('', $col1) ?>
	</div>
	<div class="groups-tools-col col-12 col-md-6">
		<?= implode('', $col2) ?>
	</div>
</div>
