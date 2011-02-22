<?php
/**
 * Elgg RSS output pageshell
 *
 * @package Elgg
 * @subpackage Core
 *
 */

header("Content-Type: text/xml");

// allow caching as required by stupid MS products for https feeds.
header('Pragma: public', TRUE);

echo "<?xml version='1.0'?>\n";

// Set title
if (empty($vars['title'])) {
	$title = elgg_get_config('sitename');
} else {
	$title = elgg_get_config('sitename') . ": " . $vars['title'];
}

// Remove RSS from URL
$url = str_replace('?view=rss','', full_url());
$url = str_replace('&view=rss','', $url);

?>

<rss version="2.0" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:georss="http://www.georss.org/georss" <?php echo elgg_view('extensions/xmlns'); ?> >
<channel>
	<title><![CDATA[<?php echo $title; ?>]]></title>
	<link><?php echo htmlentities($url); ?></link>
	<?php echo elgg_view('extensions/channel'); ?>
	<?php

		echo $vars['body'];

	?>
</channel>
</rss>
