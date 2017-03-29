<?php
$entity = elgg_extract('entity', $vars);
if (!$entity instanceof ElggFile) {
	return;
}

$menu = elgg_view_menu('entity', [
	'entity' => $vars['entity'],
	'handler' => 'file',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
		]);
?>
<div class="file-gallery-item card">
	<?php
	echo elgg_view('output/img', [
		'src' => $entity->getIconURL('large'),
		'alt' => $entity->getDisplayName(),
		'class' => 'card-img-top',
	]);
	?>
	<div class="card-block">
		<?= $menu ?>
		<h5 class="card-title">
			<?=
			elgg_view('output/url', [
				'text' => $entity->getDisplayName(),
				'href' => $entity->getURL(),
			]);
			?>
		</h5>
		<div class="elgg-subtext">
			<?= elgg_view('object/elements/imprint', $vars) ?>
		</div>
	</div>
</div>