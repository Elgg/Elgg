<?php
/**
 * Elgg footer
 * The standard HTML footer that displays across the site
 *
 * @package Elgg
 * @subpackage Core
 *
 */

?>
<div class="elgg-page-footer">
	<div class="elgg-inner clearfix">
		<?php echo elgg_view_menu('footer', array('class' => 'elgg-menu-footer')); ?>
		<?php echo elgg_view('footer/links'); ?>
		<a href="http://www.elgg.org" class="elgg-alt">
			<img src="<?php echo elgg_get_site_url(); ?>_graphics/powered_by_elgg_badge_drk_bckgnd.gif" alt="Powered by Elgg" />
		</a>
	</div>
</div>

<?php echo elgg_view('footer/analytics'); ?>
