<?php
/**
 * Elgg twitter view page
 */

$twitter_username = $vars['entity']->twitter;

// if the twitter username is empty, then do not show
if($twitter_username){
?>
<div id="profile_content">
	<ul id="twitter_update_list"></ul>
	<p class="visit_twitter"><a href="http://twitter.com/<?php echo $twitter_username; ?>" target="_blank"><?php echo elgg_echo("twitter:visit"); ?></a></p>
	<script type="text/javascript" src="http://twitter.com/javascripts/blogger.js"></script>
	<script type="text/javascript" src="http://twitter.com/statuses/user_timeline/<?php echo $twitter_username; ?>.json?callback=twitterCallback2&count=10"></script>
</div>

<?php
}