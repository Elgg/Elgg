<?php

/**
 * Layout content
 *
 * @uses $vars['content'] Content
 */

$content = elgg_extract('content', $vars, '');
?>
<div class="elgg-layout-content clearfix">
	<?= $content ?>
</div>
