<?php
/**
 * Page shell for theme preview
 */

$elgg = elgg_get_simplecache_url('css', 'elgg');
$ie_url = elgg_get_simplecache_url('css', 'ie');
$ie6_url = elgg_get_simplecache_url('css', 'ie6');

// Set the content type
header("Content-type: text/html; charset=UTF-8");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body>
<div class="elgg-page elgg-page-default">
	<div class="elgg-page-header">
		<div class="elgg-inner">
			<h1 class="elgg-heading-site">Theme Preview</h1>
		</div>
	</div>
	<div class="elgg-page-body">
		<div class="elgg-inner">
			<?php echo elgg_view('page/elements/body', $vars); ?>
		</div>
	</div>
</div>
</body>
</html>