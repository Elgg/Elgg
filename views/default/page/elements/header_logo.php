<?php
/**
 * Elgg header logo
 */

$site = elgg_get_site_entity();
$site_name = $site->name;
$site_url = elgg_get_site_url();
?>

<h1 class="elgg-heading-site">
	<?=
	elgg_format_element('a', [
		'href' => $site_url,
	], $site_name);
	?>
</h1>
