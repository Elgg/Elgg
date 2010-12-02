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
<div id="elgg-content" class="clearfix">
	<div id="elgg-page-contents" class="clearfix one_column">
		<?php echo $vars['content']; ?>
		<?php echo $vars['area1']; ?>
	</div>
</div>