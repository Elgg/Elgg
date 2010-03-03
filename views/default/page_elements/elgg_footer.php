<?php
/**
 * Elgg footer
 * The standard HTML footer that displays across the site
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */

?>
<div id="elgg_footer">
	<div id="elgg_footer_contents">
		<?php
			if(is_plugin_enabled('reportedcontent') && isloggedin()){
		?>
			<div id="report_this">
				<a href="javascript:location.href='<?php echo $vars['url']; ?>mod/reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)">Report this</a>				
			</div>
		<?php
			}
		?>
		<?php echo elgg_view('footer/links'); ?>
		<a href="http://www.elgg.org" target="_blank" class="powered_by_elgg_badge">
			<img src="<?php echo $vars['url']; ?>_graphics/powered_by_elgg_badge_drk_bckgnd.gif" border="0" alt="Powered by Elgg" />
		</a>
	</div>
</div>
