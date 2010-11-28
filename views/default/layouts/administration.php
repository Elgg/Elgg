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
	<span class="network_title"><h2>
	<a href="<?php echo elgg_get_site_url(); ?>">
	<?php echo $vars['config']->sitename; echo " ".elgg_echo('admin'); ?></a>
	<a class="return_to_network" href="<?php echo elgg_get_site_url(); ?>">&lt;&lt; Return to network</a>
	</h2></span>
</div>

<div id="elgg_content" class="clearfix admin_area">
	
	<div id="elgg_page_contents" class="clearfix">
		<?php
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
	<div id="elgg_sidebar" class="clearfix">
		<?php
			echo elgg_view('page_elements/sidebar', $vars);
		?>
	</div>
</div>
<div id="admin_footer"></div>