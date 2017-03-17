<?php

/**
 * Second layout sidebar
 *
 * @uses $vars['sidebar_alt'] Sidebar view
 */

$sidebar_alt = elgg_extract('sidebar_alt', $vars);
if (!$sidebar_alt) {
	return;
}
?>
<div class="elgg-sidebar-alt elgg-layout-sidebar-alt clearfix">
	<?= $sidebar_alt ?>
</div>
