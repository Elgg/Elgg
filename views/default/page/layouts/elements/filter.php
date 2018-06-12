<?php
/**
 * Layout content filter
 *
 * @uses $vars['filter']       - false or '' for no filter
 *                             - a string for self defined filter content
 *                             - null will render a filter menu
 *                             - an optional array of filter tabs
 *                                 Array items should be suitable for usage with
 *                                 elgg_register_menu_item()
 * @uses $vars['filter_id']    An optional ID of the filter
 *                             If provided, plugins can adjust filter tabs menu
 *                             via 'register, menu:filter:$filter_id' hook
 * @uses $vars['filter_value'] Optional name of the selected filter tab
 *                             If not provided, will be determined by current page's URL
 */

$filter = elgg_extract('filter', $vars);
if ($filter === false || $filter === '') {
	// filter disabled
	return;
}

if (!isset($filter) || is_array($filter)) {
	// make a filter menu
	$filter = elgg_view('navigation/filter', $vars);
}

if (!$filter) {
	// no content for the filter layout
	return;
}

?>
<div class="elgg-layout-filter clearfix">
	<?= $filter ?>
</div>
