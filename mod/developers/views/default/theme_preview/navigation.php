<?php
/**
 * Navigation CSS
 */

$url = current_page_url();

elgg_push_breadcrumb('First', "$url#");
elgg_push_breadcrumb('Second', "$url#");
elgg_push_breadcrumb('Third');

?>
<div class="elgg-page mal">
	<?php echo elgg_view('theme_preview/header', $vars); ?>
	<h2>Breadcrumbs</h2>
	<div class="mbl">
		<?php echo elgg_view('navigation/breadcrumbs'); ?>
	</div>
	<h2>Tabs</h2>
	<div class="mbl">
		<?php
		$tabs = array(
			array('title' => 'First', 'url' => "$url#"),
			array('title' => 'Second', 'url' => "$url#", 'selected' => true),
			array('title' => 'Third', 'url' => "$url#"),
		);
		echo elgg_view('navigation/tabs', array('tabs' => $tabs));
		?>
	</div>
	<h2>Pagination</h2>
	<div class="mbl">
		<?php
		$params = array(
			'count' => 1000,
			'limit' => 10,
			'offset' => 230,
		);
		echo elgg_view('navigation/pagination', $params);
		?>
	</div>
	<h2>Site Menu</h2>
	<div class="mbl">
		<div class="elgg-page-header" style="height: 40px;">
	<?php
		$params = array();
		$params['menu'] = array();
		$params['menu']['default'] = array();
		for ($i=1; $i<=5; $i++) {
			$params['menu']['default'][] = new ElggMenuItem($i, "Page $i", "$url#");
		}
		$params['menu']['default'][2]->setSelected(true);
		echo elgg_view('navigation/menu/site', $params);
	?>
		</div>
	</div>
	<h2>Page Menu</h2>
	<div class="mbl pam" style="width: 200px; background-color: #ccc;">
	<?php
		$m = new ElggMenuItem(10, "Child", "$url#");
		$m->setParent($params['menu']['default'][1]);
		$params['menu']['default'][1]->addChild($m);
		echo elgg_view('navigation/menu/page', $params);
	?>
	</div>
</div>
