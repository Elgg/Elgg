<?php
/**
 * Elgg plugin settings
 *
 * @package Elgg
 * @subpackage Core
 */

$plugin = $vars['plugin'];
$plugin_info = load_plugin_manifest($plugin);

$form_body = elgg_view("settings/{$plugin}/edit", $vars);
$form_body .= elgg_view('input/hidden', array('internalname' => 'plugin', 'value' => $plugin));
$form_body .= elgg_view('input/submit', array('value' => elgg_echo('save')));
//$form_body .= elgg_view('input/reset', array('value' => elgg_echo('reset')));

?>
<div class="elgg-module elgg-inline-module">
	<div class="elgg-head">
		<h3><?php echo $plugin_info['name']; ?></h3>
	</div>
	<div class="elgg-body">
		<?php
			echo elgg_view('input/form', array(
				'body' => $form_body,
				'internalid' => 'plugin_settings',
				'action' => "action/plugins/settings/save",
			));
		?>
	</div>
</div>
