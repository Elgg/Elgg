<?php
/**
 * Displays an invalid plugin on the admin screen.
 *
 * An invalid plugin is a plugin whose isValid() method returns false.
 * This usually means there are required files missing, unreadable or in the
 * wrong format.
 *
 * @package Elgg.Core
 * @subpackage Plugins
 */

$plugin = elgg_get_array_value('plugin', $vars);
$id = $plugin->getID();
$path = htmlspecialchars($plugin->getPath());
$message = elgg_echo('admin:plugins:warning:invalid', array($id));

?>

<div class="elgg-plugin elgg-state-inactive elgg-state-error">
	<p><?php echo $message; ?></p>
	<p><?php echo elgg_echo('admin:plugins:label:location') . ": " . $path; ?></p>
</div>
