<?php

/**
 * Elgg twitter view page
 *
 * @package ElggTwitter
 */

$username = $vars['entity']->twitter_username;

if (empty($username)) {
	echo "<p>" . elgg_echo("twitter:notset") . "</p>";
	return;
}

$username_is_valid = preg_match('~^[a-zA-Z0-9_]{1,20}$~', $username);
if (!$username_is_valid) {
	echo "<p>" . elgg_echo("twitter:invalid") . "</p>";
	return;
}


$num = $vars['entity']->twitter_num;
if (empty($num)) {
	$num = 5;
}

// @todo upgrade to 1.1 API https://dev.twitter.com/docs/api/1.1/get/statuses/home_timeline
$script_url = "https://api.twitter.com/1/statuses/user_timeline/" . urlencode($username) . ".json"
            . "?callback=twitterCallback2&count=" . (int) $num;

?>
<div id="twitter_widget">
	<ul id="twitter_update_list"></ul>
	<p class="visit_twitter"><?php echo elgg_view('output/url', array(
		'text' => elgg_echo("twitter:visit"),
		'href' => 'http://twitter.com/' . urlencode($username),
		'is_trusted' => true,
	)) ?></p>
	<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
	<script type="text/javascript" src="<?php echo htmlspecialchars($script_url, ENT_QUOTES, 'UTF-8') ?>"></script>
</div>
