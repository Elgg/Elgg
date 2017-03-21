<?php

/**
 * Layout sidebar
 *
 * @uses $vars['sidebar'] Sidebar view
 */

$sidebar = elgg_extract('sidebar', $vars);
if ($sidebar === false) {
	return;
}
?>
<div class="elgg-sidebar elgg-layout-sidebar clearfix">
	<?= $sidebar ?>
</div>
