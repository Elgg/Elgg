<?php
/**
 * Extension of login form for Twitter sign in
 */

$url = elgg_get_site_url() . 'twitter_api/forward';
$img_url = elgg_get_site_url() . 'mod/twitter_api/graphics/sign-in-with-twitter-d.png';

$login = <<<__HTML
<div id="login_with_twitter">
	<a href="$url">
		<img src="$img_url" alt="Twitter" />
	</a>
</div>
__HTML;

echo $login;
