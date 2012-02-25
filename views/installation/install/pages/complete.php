<?php
/**
 * Install completion page
 */

echo elgg_view('output/longtext', array('value' => elgg_echo('install:complete:instructions')));

?>

<div class="elgg-install-nav">
<?php
	$url = elgg_get_site_url() . $vars['destination'];
	$text = elgg_echo('install:complete:gotosite');
	echo "<a href=\"$url\">$text</a>";
?>
</div>
