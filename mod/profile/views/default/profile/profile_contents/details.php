<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */

// Simple XFN
$rel = "";
if (elgg_get_page_owner_guid() == $vars['entity']->guid) {
	$rel = 'me';
} else if (check_entity_relationship(elgg_get_page_owner_guid(), 'friend', $vars['entity']->guid)) {
	$rel = 'friend';
}

$even_odd = null;
if (is_array($vars['config']->profile) && sizeof($vars['config']->profile) > 0) {
	foreach($vars['config']->profile as $shortname => $valtype) {
		if ($shortname != "description") {
			$value = $vars['entity']->$shortname;
			if (!empty($value)) {
				//This function controls the alternating class
				$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
				?>
				<p class="<?php echo $even_odd; ?>">
				<b><?php
						echo elgg_echo("profile:{$shortname}");
				?>: </b>
				<?php
					echo elgg_view("output/{$valtype}", array('value' => $vars['entity']->$shortname));
				?>
				</p>
				<?php
			}
		}
	}
}

if (!get_plugin_setting('user_defined_fields', 'profile')) {
	if ($vars['entity']->isBanned()) {
		echo "<div class='banned_user'>";
		echo elgg_echo('profile:banned');
		echo "</div>";
	} else {
		if ($vars['entity']->description) {
			echo "<p class='aboutme_title'><b>" . elgg_echo("profile:aboutme") . "</b></p>";
			echo "<div class='aboutme_contents'>" . elgg_view('output/longtext', array('value' => $vars['entity']->description)) . "</div>";
		}
	}
}