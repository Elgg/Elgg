<?php
/**
 * Elgg user display (details)
 * @uses $vars['entity'] The user entity
 */

$user = elgg_get_page_owner_entity();

$profile_fields = elgg_get_config('profile_fields');

echo '<div id="profile-details" class="elgg-body pll">';
echo "<span class=\"hidden nickname p-nickname\">{$user->username}</span>";
echo "<h2 class=\"p-name fn\">{$user->name}</h2>";

// the controller doesn't allow non-admins to view banned users' profiles
if ($user->isBanned()) {
	$title = elgg_echo('banned');
	$reason = ($user->ban_reason === 'banned') ? '' : $user->ban_reason;
	echo "<div class='profile-banned-user'><h4 class='mbs'>$title</h4>$reason</div>";
}

echo elgg_view("profile/status", array("entity" => $user));

$microformats = array(
	'mobile' => 'tel p-tel',
	'phone' => 'tel p-tel',
	'website' => 'url u-url',
	'contactemail' => 'email u-email',
);

$even_odd = null;
if (is_array($profile_fields) && sizeof($profile_fields) > 0) {
	foreach ($profile_fields as $shortname => $valtype) {
		if ($shortname == "description") {
			// skip about me and put at bottom
			continue;
		}
		$value = $user->$shortname;

		if (!is_null($value)) {

			// fix profile URLs populated by https://github.com/Elgg/Elgg/issues/5232
			// @todo Replace with upgrade script, only need to alter users with last_update after 1.8.13
			if ($valtype == 'url' && $value == 'http://') {
				$user->$shortname = '';
				continue;
			}

			// validate urls
			if ($valtype == 'url' && !preg_match('~^https?\://~i', $value)) {
				$value = "http://$value";
			}

			// this controls the alternating class
			$even_odd = ( 'odd' != $even_odd ) ? 'odd' : 'even';
			?>
			<div class="<?php echo $even_odd; ?>">
				<b><?php echo elgg_echo("profile:{$shortname}"); ?>: </b>
				<?php
					$params = array(
						'value' => $value
					);
					if (isset($microformats[$shortname])) {
						$class = $microformats[$shortname];
					} else {
						$class = '';
					}
					echo "<span class=\"$class\">";
					echo elgg_view("output/{$valtype}", $params);
					echo "</span>";
				?>
			</div>
			<?php
		}
	}
}

if ($user->description) {
	echo "<p class='profile-aboutme-title'><b>" . elgg_echo("profile:aboutme") . "</b></p>";
	echo "<div class='profile-aboutme-contents'>";
	echo elgg_view('output/longtext', array('value' => $user->description, 'class' => 'mtn'));
	echo "</div>";
}

echo '</div>';