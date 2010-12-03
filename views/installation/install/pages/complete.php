<?php
/**
 * Install completion page
 */

echo autop(elgg_echo('install:complete:instructions'));

?>

<div class="install-nav">
<?php
	$url = elgg_get_site_url() . $vars['destination'];
	$text = elgg_echo('install:complete:gotosite');
	echo "<a href=\"$url\">$text</a>";
?>
</div>
