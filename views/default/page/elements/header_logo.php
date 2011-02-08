<?php
/**
 * Elgg header logo
 * The logo to display in elgg-header.
 */

$site = elgg_get_site_entity();
$site_name = $site->name;
?>

<h1>
	<a class="elgg-heading-site" href="<?php echo elgg_get_site_url(); ?>"><?php echo $site_name; ?></a>
</h1>
