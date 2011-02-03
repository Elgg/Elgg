<?php
/**
 * 
 */

$url = elgg_get_site_url() . 'pg/twitterservice/forward';
$img_url = elgg_get_site_url() . 'mod/twitterservice/graphics/sign_in_with_twitter.gif';

$login = <<<__HTML
<div id="login_with_twitter">
	<a href="$url">
		<img src="$img_url" alt="Twitter" />
	</a>
</div>
__HTML;

echo $login;
