<?php
/**
 * Install completion page
 */

echo autop(elgg_echo('install:complete:instructions'));

?>

<div class="install_nav">
<?php
	$text = elgg_echo('install:complete:gotosite');
	echo "<a href=\"{$vars['url']}index.php\">$text</a>";
?>
</div>
