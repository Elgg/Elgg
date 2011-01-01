<?php
/**
 * Provide a way of setting your language prefs
 *
 * @package Elgg
 * @subpackage Core
 */

global $CONFIG;
$user = elgg_get_page_owner();

if ($user) {
?>
<div class="elgg-module elgg-info-module">
	<div class="elgg-head">
		<h3><?php echo elgg_echo('user:set:language'); ?></h3>
	</div>
	<div class="elgg-body">
		<p>
			<?php echo elgg_echo('user:language:label'); ?>:
			<?php
			echo elgg_view("input/pulldown", array(
				'internalname' => 'language',
				'value' => $value,
				'options_values' => get_installed_translations()
			));
			?>
		</p>
	</div>
</div>
<?php
}