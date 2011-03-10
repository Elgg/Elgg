<?php
/**
 * @package Elgg
 * @subpackage Core
 * @deprecated 1.7
 */
elgg_deprecated_notice('view groups/search/finishblurb was deprecated.', 1.7);

if ($vars['count'] > $vars['threshold']) {

?>
<div class="contentWrapper"><a href="<?php echo elgg_get_site_url(); ?>search/groups?tag=<?php echo urlencode($vars['tag']); ?>">
	<?php
	echo elgg_echo("group:search:finishblurb");
	?></a>
</div>
<?php
}
