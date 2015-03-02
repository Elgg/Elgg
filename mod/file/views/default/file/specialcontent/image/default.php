<?php
/**
 * Display an image
 *
 * @uses $vars['entity']
 */

$file = $vars['entity'];

$image_url = $file->getIconURL('large');
$image_url = elgg_format_url($image_url);
$download_url = elgg_get_site_url() . "file/download/{$file->getGUID()}";

if ($vars['full_view']) {
	elgg_load_js('lightbox');
	elgg_load_css('lightbox');
	echo <<<HTML
		<div class="file-photo">
			<a href="$download_url" class="elgg-lightbox-photo"><img class="elgg-photo" src="$image_url" /></a>
		</div>
HTML;
}
