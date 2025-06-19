<?php
/**
 * Maintenance body
 *
 * @uses $vars['messages'] System messages
 * @uses $vars['header'] Page header
 * @uses $vars['content'] Page content
 */
?>
<div class="elgg-page elgg-page-maintenance">
	<div class="elgg-page-maintenance-background"></div>
	<div class="elgg-page-messages">
		<?= elgg_extract('messages', $vars); ?>
	</div>
	<div class="elgg-inner">
		<header class="elgg-page-header">
			<div class="elgg-inner">
				<?= elgg_extract('header', $vars); ?>
			</div>
		</header>
		<main class="elgg-page-body">
			<div class="elgg-inner">
				<?= elgg_extract('content', $vars); ?>
			</div>
		</main>
	</div>
</div>
