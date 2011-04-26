<?php 

$params = array();
$params['menu'] = array();
$params['menu']['default'] = array();
for ($i=1; $i<=5; $i++) {
	$params['menu']['default'][] = new ElggMenuItem($i, "Page $i", "$url#");
}
$params['menu']['default'][2]->setSelected(true);
?>

<div class="elgg-page-header">
	<div class="elgg-inner">
	<?php 
		echo elgg_view('navigation/menu/site', $params); 
	?>
	</div>
</div>
