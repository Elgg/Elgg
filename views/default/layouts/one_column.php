<?php
/**
 * Elgg one-column layout
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['content'] Content string
 */
?>
<div class="elgg-body">
	<div class="elgg-layout elgg-center elgg-width-classic clearfix">
		<?php echo $vars['content']; ?>
		<?php echo $vars['area1']; ?>
	</div>
</div>