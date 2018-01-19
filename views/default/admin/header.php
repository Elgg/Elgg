<?php

/**
 * Topbar
 */
elgg_require_js('page/elements/topbar');
?>

<div class="elgg-nav-logo">
	<h1 class="elgg-heading-site">
		<a href="<?= elgg_get_site_url(); ?>admin">
			<?= elgg_get_site_entity()->getDisplayName() ?>
			<small><?= elgg_echo('admin') ?></small>
		</a>
	</h1>
</div>

<div class="elgg-nav-button">
	<span></span>
	<span></span>
	<span></span>
</div>

<div class="elgg-nav-collapse">
	<?php
	echo elgg_view_menu('admin_header');
	?>
</div>
