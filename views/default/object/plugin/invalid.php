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
$message = elgg_echo('admin:plugins:warning:invalid', array($plugin->getError()));
$css_id = preg_replace('/[^a-z0-9-]/i', '-', $plugin->getID());

?>

<div class="elgg-state-draggable elgg-plugin elgg-state-inactive elgg-state-error" id="elgg-plugin-<?php echo $plugin->guid; ?>">
	<div class="elgg-head"><h3><?php echo $id; ?></h3></div>
	<div class="elgg-body">
		<p class="elgg-state-error"><?php echo $message; ?></p>
		<p><?php echo elgg_echo('admin:plugins:warning:invalid:check_docs'); ?></p>
		
		<div class="pts">
			<?php
				echo elgg_view('output/url', array(
					'href' => "#elgg-plugin-manifest-$css_id",
					'text' => elgg_echo("admin:plugins:label:moreinfo"),
					'rel' => 'toggle',
				));
			?>
		</div>

		<div class="hidden elgg-plugin-more" id="elgg-plugin-manifest-<?php echo $css_id; ?>">
			<p><?php echo elgg_echo('admin:plugins:label:location') . ": " . $path; ?></p>
		</div>
	</div>
</div>