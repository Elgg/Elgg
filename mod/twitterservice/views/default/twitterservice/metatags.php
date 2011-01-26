<?php
/**
 * Adds required HTML head tags for Twitter Services.
 *
 * @package TwitterService
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @copyright Curverider Ltd 2010
 */

if ($api_key = get_plugin_setting('consumer_key', 'twitterservice')) {
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
