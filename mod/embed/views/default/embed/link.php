<?php
/**
 * Displays an "Embed media" link in longtext inputs.
 */

// yeah this is naughty.  embed and ecml might want to merge.
if (elgg_is_active_plugin('ecml')) {
	$active_section = 'active_section=web_services&';
} else {
	$active_section = '';
}

$url = "pg/embed/?{$active_section}internal_name={$vars['name']}";
$url = elgg_normalize_url($url);

?>
<a class="elgg-longtext-control" href="<?php echo $url; ?>" rel="facebox">
	<?php echo elgg_echo('media:insert'); ?>
</a>
