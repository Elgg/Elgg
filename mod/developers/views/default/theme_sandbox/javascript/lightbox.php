<?php

echo elgg_view('output/url', [
	'text' => 'Open lightbox',
	'href' => "ajax/view/developers/ajax",
	'class' => 'elgg-lightbox'
]);

echo elgg_view('output/url', [
	'text' => 'Open iframe lightbox',
	'href' => 'https://elgg.org',
	'class' => 'elgg-lightbox-iframe mll',
	'data-colorbox-opts' => json_encode([
		'width' => '80%',
		'height' => '80%',
	]),
]);

echo elgg_view('output/url', [
	'text' => 'Open inline HTML lightbox',
	'href' => '#lightbox-inline',
	'class' => 'elgg-lightbox-inline mll',
]);

elgg_require_js('theme_sandbox/javascript/lightbox');

?>
<div class="hidden">
	<div id="lightbox-inline">
		<?= elgg_view('developers/ipsum') ?>
	</div>
</div>
<?php
$files = elgg_get_entities([
	'types' => 'object',
	'subtypes' => 'file',
	'metadata_name_value_paris' => [
		'name' => 'simpletype',
		'value' => 'image',
	],
		]);

if (!$files) {
	return;
}

echo elgg_view('output/url', [
	'text' => 'Open photo lightbox',
	'href' => elgg_get_download_url($files[0]),
	'class' => 'elgg-lightbox-photo mll',
]);
?>
<ul class="elgg-gallery elgg-gallery-fluid">
	<?php
	foreach ($files as $file) {
		?>
		<li class="pam">
			<?php
			echo elgg_view('output/url', [
				'text' => elgg_view('output/img', [
					'src' => $file->getIconURL('small'),
					'alt' => $file->getDisplayName(),
				]),
				'href' => $file->getIconURL('large'),
				'rel' => 'lightbox-gallery',
			]);
			?>
		</li>
		<?php
	}
	?>
</ul>

