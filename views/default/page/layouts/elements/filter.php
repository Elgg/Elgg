<?php

/**
 * Layout content filter
 *
 * @uses $vars['filter']       An optional array of filter tabs
 *                             Array items should be suitable for usage with
 *                             elgg_register_menu_item()
 * @uses $vars['filter_id']    An optional ID of the filter
 *                             If provided, plugins can adjust filter tabs menu
 *                             via 'register, menu:filter:$filter_id' hook
 * @uses $vars['filter_value'] Optional name of the selected filter tab
 *                             If not provided, will be determined by current page's URL
 */

$filter = elgg_view('navigation/filter', $vars);
if (!$filter) {
	return;
}
?>
<div class="elgg-layout-filter clearfix">
	<?= $filter ?>
</div>
