<?php
/**
 * Layout content
 *
 * @uses $vars['content'] Content
 */
$content = elgg_extract('content', $vars, '');
$sidebar = elgg_extract('sidebar', $vars, '');
$sidebar_alt = elgg_extract('sidebar_alt', $vars, '');
?>
<div class="elgg-layout-content clearfix">
	<div class="elgg-inner container d-flex flex-column flex-md-row">
		<?= $sidebar ?>
		<?= $content ?>
		<?= $sidebar_alt ?>
	</div>
</div>
