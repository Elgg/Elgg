<?php

elgg_register_menu_item('site', [
	'name' => 'parent',
	'text' => 'Parent 1',
	'href' => false,
]);

elgg_register_menu_item('site', [
	'name' => 'child1',
	'parent_name' => 'parent',
	'text' => 'Child 1',
	'href' => false,
]);

elgg_register_menu_item('site', [
	'name' => 'child2',
	'parent_name' => 'parent',
	'text' => 'Child 2',
	'href' => false,
]);

elgg_register_menu_item('site', [
	'name' => 'grandchild1',
	'parent_name' => 'child1',
	'text' => 'Grandchild 1',
	'href' => false,
]);

elgg_register_menu_item('site', [
	'name' => 'grandchild2',
	'parent_name' => 'child1',
	'text' => 'Grandchild 2',
	'href' => false,
]);

elgg_register_menu_item('topbar', [
	'name' => 'parent',
	'parent_name' => 'account',
	'text' => 'Parent 1',
	'href' => false,
	'section' => 'alt',
]);

elgg_register_menu_item('topbar', [
	'name' => 'child1',
	'parent_name' => 'parent',
	'text' => 'Child 1',
	'href' => false,
	'section' => 'alt',
]);

elgg_register_menu_item('topbar', [
	'name' => 'child2',
	'parent_name' => 'parent',
	'text' => 'Child 2',
	'href' => false,
	'section' => 'alt',
]);

elgg_register_menu_item('topbar', [
	'name' => 'grandchild1',
	'parent_name' => 'child1',
	'text' => 'Grandchild 1',
	'href' => false,
	'section' => 'alt',
]);

elgg_register_menu_item('topbar', [
	'name' => 'grandchild2',
	'parent_name' => 'child1',
	'text' => 'Grandchild 2',
	'href' => false,
	'section' => 'alt',
]);

?>

<div class="elgg-page-section elgg-page-topbar">
	<div class="elgg-inner">
		<div class="elgg-nav-button">
			<span></span>
			<span></span>
			<span></span>
		</div>

		<div class="elgg-nav-collapse">
			<?php
			echo elgg_view_menu('site', ['prepare_toggle' => false]);
			echo elgg_view_menu('topbar', ['prepare_toggle' => false]);
			?>
		</div>
	</div>
</div>
