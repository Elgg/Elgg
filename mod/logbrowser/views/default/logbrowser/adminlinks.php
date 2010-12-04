<?php
/**
 * The dropdown link for exploring a user's log
 *
 * @package ElggLogBrowser
 */
?>
<a href="<?php echo elgg_get_site_url(); ?>pg/admin/overview/logbrowser/?user_guid=<?php echo $vars['entity']->guid; ?>">
	<?php echo elgg_echo("logbrowser:explore"); ?>
</a>