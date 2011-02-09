<?php
/**
 * Elgg plugin
 *
 * @package Elgg
 * @subpackage Core
 */

$entity = $vars['entity'];
$plugin = $vars['plugin'];
$plugin_id = $plugin->getID();
$prefix = $vars['prefix']; // Do we want to show admin settings (default) or user settings

$form_body = elgg_view("{$prefix}settings/{$plugin_id}/edit", $vars)
	. "<p>" . elgg_view('input/hidden', array('internalname' => 'plugin', 'value' => $plugin_id))
	. elgg_view('input/submit', array('value' => elgg_echo('save'))) . "</p>";

?>
<div>
	<?php echo elgg_view('input/form', array('body' => $form_body, 'action' => "action/plugins/{$prefix}settings/save")); ?>
</div>