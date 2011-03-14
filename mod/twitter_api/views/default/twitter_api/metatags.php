<?php
/**
 * Adds required HTML head tags for Twitter Services.
 *
 * @package TwitterAPI
 */

if ($api_key = elgg_get_plugin_setting('consumer_key', 'twitter_api')) {
	$tags = <<<__HTML
<script src="http://platform.twitter.com/anywhere.js?id=$api_key&v=1" type="text/javascript"></script>
<script type="text/javascript">
	twttr.anywhere(function (T) {
		T(".twitter_anywhere").hovercards();
	});
</script>
__HTML;

	echo $tags;
}
