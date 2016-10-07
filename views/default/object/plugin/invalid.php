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
/* @var ElggPlugin $plugin */
$plugin = $vars['entity'];

$id = $plugin->getID();
$path = htmlspecialchars($plugin->getPath());
$message = elgg_echo('admin:plugins:warning:invalid', array($plugin->getError()));
$css_id = preg_replace('/[^a-z0-9-]/i', '-', $plugin->getID());
?>

<div class="elgg-state-draggable elgg-plugin elgg-state-inactive elgg-state-cannot-activate" id="<?php echo $css_id; ?>">
	<div class="elgg-image-block">
		<div class="elgg-image">
			<div>
				<?php
				echo elgg_view('output/url', [
					'href' => '',
					'text' => elgg_echo('admin:plugins:cannot_activate'),
				]);
				?>
			</div>
		</div>
		<div class="elgg-body">
			<div class="elgg-head">
				<div class="elgg-plugin-title">
					<?php echo $id ?>
				</div>
			</div>
			<div class="elgg-body">
				<p class="elgg-text-help elgg-state-error">
					<?php echo $message; ?>
					<?php echo elgg_echo('admin:plugins:label:location') . ": " . $path; ?>
				</p>
				<p class="elgg-text-help"><?php echo elgg_echo('admin:plugins:warning:invalid:check_docs'); ?></p>
			</div>
		</div>
	</div>
</div>