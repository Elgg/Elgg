<?php
/**
 * Elgg Admin Area Canvas
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 * @uses $vars['sidebar'] Optional sidebar content
 */
?>

<div id="admin_header">
	<span class="network-title"><h2>
	<a href="<?php echo elgg_get_site_url(); ?>">
	<?php echo $vars['config']->sitename; echo " ".elgg_echo('admin'); ?></a>
	<a class="return_to_network" href="<?php echo elgg_get_site_url(); ?>">&lt;&lt; Return to network</a>
	</h2></span>
</div>

<div id="elgg-content" class="clearfix admin_area">
	
	<div id="elgg-page-contents" class="clearfix">
		<?php
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
	<div id="elgg-sidebar" class="clearfix">
		<?php
			echo elgg_view('layout/elements/sidebar', $vars);
		?>
	</div>
</div>
<div id="admin_footer"></div>