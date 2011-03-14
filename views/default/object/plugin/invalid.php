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

$plugin = $vars['entity'];

$id = $plugin->getID();
$path = htmlspecialchars($plugin->getPath());
$message = elgg_echo('admin:plugins:warning:invalid', array($id));
$error = $plugin->getError();

?>

<div class="elgg-state-draggable elgg-plugin elgg-state-inactive elgg-state-error" id="elgg-plugin-<?php echo $plugin->guid; ?>">
	<div class="elgg-head"><h3><?php echo $id; ?></h3></div>
	<div class="elgg-body">
		<p><?php echo $message; ?></p>
		
		<div class="pts">
			<?php
				echo elgg_view('output/url', array(
					'href' => "#elgg-plugin-manifest-{$plugin->getID()}",
					'text' => elgg_echo("admin:plugins:label:moreinfo"),
					'class' => 'elgg-toggler',
				));
			?>
		</div>

		<div class="hidden manifest_file" id="elgg-plugin-manifest-<?php echo $plugin->getID(); ?>">
			<p><?php echo elgg_echo('admin:plugins:label:location') . ": " . $path; ?></p>
			<p><?php echo $error; ?></p>
		</div>
	</div>
</div>