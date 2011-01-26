<?php
/**
 * 
 */

$url = "{$vars['url']}pg/twitterservice/forward";

$login = <<<__HTML
<div id="login_with_twitter">
	<a href="$url">
		<img src="{$vars['url']}mod/twitterservice/graphics/sign_in_with_twitter.gif" alt="Twitter" />
	</a>
</div>
__HTML;

echo $login;
