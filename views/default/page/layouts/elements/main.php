<?php

/**
 * Main content column
 *
 * @uses $vars['content'] Content view
 */

$content = elgg_extract('content', $vars);
if ($content === false) {
	return;
}
?>
<div class="elgg-main elgg-body elgg-layout-main clearfix">
	<?= $content ?>
</div>
