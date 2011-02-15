<?php
/**
 * Provide a way of setting your default access
 *
 * @package Elgg
 * @subpackage Core
 */
if (elgg_get_config('allow_user_default_access')) {
	$user = elgg_get_page_owner_entity();

	if ($user) {
		if (false === ($default_access = $user->getPrivateSetting('elgg_default_access'))) {
			$default_access = elgg_get_config('default_access');
		}
?>
<div class="elgg-module elgg-module-info">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('default_access:settings'); ?></h3>
	</div>
	<div class="elgg-body">
		<p>
		<?php echo elgg_echo('default_access:label'); ?>:
		<?php

			echo elgg_view('input/access', array('name' => 'default_access', 'value' => $default_access));

		?>
		</p>
	</div>
</div>
<?php
	}
}