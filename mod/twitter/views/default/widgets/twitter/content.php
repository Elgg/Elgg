<?php

/**
 * Elgg twitter view page
 *
 * @package ElggTwitter
 */

//some required params

$username = $vars['entity']->twitter_username;
$num = $vars['entity']->twitter_num;

// if the twitter username is empty, then do not show
if ($username) {

?>

<div id="twitter_widget">
	<ul id="twitter_update_list"></ul>
	<p class="visit_twitter"><a href="http://twitter.com/<?php echo $username; ?>"><?php echo elgg_echo("twitter:visit"); ?></a></p>
	<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
	<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $username; ?>.json?callback=twitterCallback2&count=<?php echo $num; ?>"></script>
</div>

<?php
} else {

	echo "<p>" . elgg_echo("twitter:notset") . ".</p>";

}
