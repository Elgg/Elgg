<?php
/**
 * Install completion page
 */

echo autop(elgg_echo('install:complete:instructions'));

?>

<div class="install_nav">
<?php
	$url = $vars['url'] . $vars['destination'];
	$text = elgg_echo('install:complete:gotosite');
	echo "<a href=\"$url\">$text</a>";
?>
</div>
