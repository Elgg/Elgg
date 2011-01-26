<?php
/**
 * Displays an "Embed media" link in longtext inputs.
 */

// yeah this is naughty.  embed and ecml might want to merge.
if (is_plugin_enabled('ecml')) {
	$active_section = 'active_section=web_services&';
} else {
	$active_section = '';
}

?>
<a class="elgg-longtext-control small link" href="<?php echo elgg_get_site_url() . 'pg/embed'; ?>?<?php echo $active_section; ?>internal_name=<?php echo $vars['internalname']; ?>" rel="facebox"><?php echo elgg_echo('media:insert'); ?></a>
