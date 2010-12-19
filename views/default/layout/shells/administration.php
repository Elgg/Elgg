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

<div class="elgg-page-header">
	<div class="elgg-inner">
		<h1>
			<a href="<?php echo elgg_get_site_url(); ?>">
				<?php echo $vars['config']->sitename; echo " " . elgg_echo('admin'); ?>
			</a>
		</h1>
		<a class="return_to_network" href="<?php echo elgg_get_site_url(); ?>">&lt;&lt; Return to network</a>
	</div>
</div>

<div class="elgg-page-body">
	
	<div class="elgg-sidebar clearfix">
		<?php
			echo elgg_view('layout/elements/sidebar', $vars);
		?>
	</div>
	<div class="elgg-main elgg-body">
		<?php
			if (isset($vars['content'])) {
				echo $vars['content'];
			}
		?>
	</div>
</div>
<div class="elgg-page-footer"></div>