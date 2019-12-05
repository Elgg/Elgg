<?php

/**
 * Layout sidebar
 *
 * @uses $vars['sidebar'] Sidebar view
 */

$sidebar = elgg_extract('sidebar', $vars);
if (empty($sidebar)) {
	return;
}
?>
<div class="elgg-sidebar elgg-layout-sidebar clearfix">
	<?= $sidebar ?>
</div>
